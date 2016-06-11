<?php

include_once '/var/www/avito/mrtin/data/www/core/dop_parser/core/db.php';

class cron extends ext
{
	private $dbB = null;

	var $__preload = [
		'core' => ['db','request'],
		'model' => ['Avito','vk']
	];
	function __construct()
	{
		$this->dbB = dbB::getInstance();

		if(is_http)
			die($this->err(''));
	}
	private function ___vk($r,$q)
	{
		static $stoppen;
		if(!is_array($stoppen))
			$stoppen = explode(',',file_get_contents(g::get('vk_stoppens')));
		
		if($stoppen)
		{
			foreach($stoppen as $st)
			{
				$st = trim($st);
				if(empty($st)) continue 1;
				if(stristr($r['content'],$st))
					return _log::set('Пропущено по стоп слову "'.$st.'"','vk');
			}
		}
		if(!empty($r['cLink']))
			$this->__vk_comments($q,$r['cLink']);
		$r['content'] = str_replace('Показать полностью..','',$r['content']);
		$ahref = (!empty($r['podp'])) ? $r['podp'] : $r['author'];
		if($this->db->query('select id from vk_users where vkhref="'.$ahref.'"')->num_rows)
		{
			return _log::set('WARNING: Автор: "'.$ahref.'" уже есть в базе, пропускаем','vk');
		}
		sleep(2);
		$author = $this->vk->cityAuthor( $ahref );
		if(empty($author[2]))
		{
			return _log::set('ERROR: Не удалось получить id автора ('.$ahref.') записи в группе','vk');
		}

		$citys = [];
		for($_i=0;$_i<2;++$_i)
		{
			$city = $author[$_i];
			if(empty($city)) continue 1;
			$citys[] = $city;
		}
		if(empty($citys) && !empty($q->citys))
		{
			return _log::set('WARNING: У автора: "'.$ahref.'" не указан город, пропускаем','vk');
		}
		$s = 0;
			
		if(!empty($q->citys))
		{
			foreach($citys as $city)
				if(array_keys($q->citys,$city))
					++$s;
		}else
			$s = 1;
		if(!$s)
		{
			return _log::set('WARNING: Город автора не проходит фильтры, пропускаем','vk');
		}

		$imgs = [];
		foreach($r['imgs'] as $img)
		{
			sleep(1);
			$fname = md5($img);
			$f = explode('.',$img); $f = $f[count($f)-1];
			$file = g::get('imgPath').$fname.'.'.$f;
			if(file_exists($file))
			{
				$imgs[] = $this->db->query('select id from vk_imgs where file = "'.($fname.'.'.$f).'"')->fetch_row()[0];
				continue 1;
			}
			if(file_put_contents(g::get('imgPath').$fname.'.'.$f,$this->vk->curl(['url'=>$img])))
			{
				$this->db->insert('vk_imgs',[
					'file' => $fname.'.'.$f,
				]);
				$imgs[] = $this->db->db->insert_id;
			}else{
				_log::set('ERROR: Не удалось скачать фото на сервер','vk');
			}
		}
		
		$this->db->insert('vk_users',[
			'vkid' => $author[2],
			'vkhref' => $ahref,
		]);
			
		$this->db->insert('vk_obs',[
			'time' => $r['time'],
			'idus' => $this->db->db->insert_id,
			'parent' => $q->id,
			'content' => $r['content'],
			'imgs' => empty($imgs) ? '' : implode(',',$imgs)
		]);
		_log::set('SUCCESS: Добавлен объект пользователя "id'.$author[2].'"','vk');
	}
	private function __vk_topic($q)
	{
		$res = $this->vk->topic($q->var);
		foreach($res as $i=>$r)
		{
			unset($res[$i]); //Памяти занято может быть дохрена, поэтому подчищаем
			$this->___vk($r,$q);
		}
	}
	private function __vk_group($q)
	{
		$res = $this->vk->group($q->var);
		foreach($res as $i=>$r)
		{
			unset($res[$i]); //Памяти занято может быть дохрена, поэтому подчищаем
			$this->___vk($r,$q);
		}
	}
	private function __vk_news($q)
	{
		$res = $this->vk->news($q->var,time()-2*24*60*60);
		foreach($res as $i=>$r)
		{
			unset($res[$i]); //Памяти занято может быть дохрена, поэтому подчищаем
			$this->___vk($r,$q);
		}
	}
	private function __vk_comments($q,$href)
	{
		$res = $this->vk->comments($href);
		foreach($res as $i=>$r)
		{
			unset($res[$i]); //Памяти занято может быть дохрена, поэтому подчищаем
			$this->___vk($r,$q);
		}
	}
	function vk_acc()
	{
		$this
			->init_obj('db')
			->init_obj('request')
			->init_obj('vk');
		$id = (int) @$this->request->uri()[2];
		$acc = $this->db->query('select phone,password from vk_account where id = '.$id);
		if(empty($acc->num_rows))
			return _log::set('ERROR: Не передан id аккаунта в роутинг крона.','vk');
		$acc = $acc->fetch_object();
		if(!$this
			->vk
			->setCookie(__DIR__.'/')
			->auth($acc->phone,$acc->password)
		)
			return _log::set('ERROR: Не удалось авторизоваться: ['.$acc->phone.','.$acc->password.']','vk');
		$sql = $this->db->query('select id,type,var,citys from vk_filters where account = '.$id.' order by id desc');
		while($q = $sql->fetch_object())
		{
			$q->citys = explode(',',$q->citys);
			foreach($q->citys as $i=>$c)
			{
				$q->citys[$i] = trim($c);
				if(empty($q->citys[$i]))
					unset($q->citys[$i]);
			}
			switch($q->type)
			{
				case 1:
					$this->__vk_group($q);
				break 1;
				case 2:
					$this->__vk_topic($q);
				break 1;
				case 0:
					$this->__vk_news($q);
			}
		}
	}
	function vk()
	{
		$sql = $this->init_obj('db')
			->db
			->query('
				select
					id
				from 
					vk_account
				where 
					(select count(id) from vk_filters where account = vk_account.id) > 0
			');
		while($r = $sql->fetch_object())
			exec('php -f '.root.'index.php cron.vk_acc.'.$r->id.' >/dev/null &');
		/*
		$this->init_obj('vk');
		if(true)
		if(!$this
			->vk
			->setCookie(__DIR__.'/')
			->auth('994509984658','rr123456')
		)
			die('FuckenAuth '."\n");
		
		var_dump(
		$this->vk
			//->news('продам гараж', (time()-2*24*60*60) ) //Поиск
			//->cityAuthor('6062121') // Вывод городов юзверя
			//->group('/club46095822') //Посты группы, пабликов
			//->comments('/wall-72495085_112166') //Комменты
			->topic('/topic-46095822_27967140')
		);
		*/
	}


	/*
	function actual()
	{
		#$sql = $this->init_obj('Avito')->init_obj('db')->db->query('
		#	select id,url,modered from obs where is_delete = 0 and (phone = "-" OR modered = 0)
		#');

		$sql = $this->init_obj('Avito')->init_obj('db')->db->query('
			select id,url,modered from obs where is_delete = 0
		');

		while($r = $sql->fetch_object())
		{
			$str = 'ps aux | grep "cron.actualMany.' . trim($r->url) . '"';
			exec($str, $ret);

			if(preg_match('#php\s+\-f#ui', $ret[0]))
			{
				continue;
			}
			else
			{
				sleep(mt_rand(7,12));
				$str = "php -f /var/www/avito/mrtin/data/www/core/index.php cron.actualMany." . $r->url . " > /dev/null &";
				exec($str);
			}
			#die('end');
		}
		echo "Ok\n";
	}
	*/

	function actual()
	{
		$sql = $this->init_obj('Avito')->init_obj('db')->db->query('
			select * from obs where is_delete = 0 and modered = 0
		');

		while($item = $sql->fetch_object())
		{
			if(preg_match('#моск#ui', $item->city))
			{
				if($this->dbB->chechAvito((array)$item))
				{
					$item->modered = 1;
				}
			}
			else if(preg_match('#санкт#ui', $item->city))
			{
				if($this->dbB->chechStopAgent((array)$item))
					$item->modered = 1;
			}
			else if(preg_match('#казан|нижний#ui', $item->city))
			{
				if($this->dbB->checkTotook((array)$item))
					$item->modered = 1;
			}

			if($item->modered == 1)
			{
				$this->init_obj('db')->db->query('update obs set modered = 1 where id = ' . $item->id);
			}
			
		}
		echo "Ok\n";
	}

	/*
	function actualMany($url)
	{
		sleep(mt_rand(5,10));
		$this->init_obj('Avito')->init_obj('db');

		$item = $this->Avito->item($url, 1, 1, 1);
		
		if(!$item) break;
		else if(is_string($item) or empty($item->id))
		{
			$this->db->update('obs',['is_delete'=>1],['id'=>$item->id]);
		}else{

			$item->modered = 0;

			if(preg_match('#москв#ui', $item->city))
			{
				if($this->dbB->chechAvito((array)$item))
				{
					$item->modered = 1;
				}
			}
			else if(preg_match('#санкт#ui', $item->city))
			{
				if($this->dbB->chechStopAgent((array)$item))
					$item->modered = 1;
			}
			else if(preg_match('#казан|нижний#ui', $item->city))
			{
				if($this->dbB->checkTotook((array)$item))
					$item->modered = 1;
			}

			/*			
				if($item->modered == 0)
				{
					if($this->dbB->chechRent((array)$item))
						$item->modered = 1;
				}
			*/

			#print_r($item);die();


	#		$this->db->update('obs',(array) $item,['id'=>$item->id]);
	#	}
	#}
	
	function actualOld($url)
	{
		#$sql = $this->init_obj('Avito')->init_obj('db')->db->query('
		#	select id,url,modered from obs where is_delete = 0 and (phone = "-" OR modered = 0)
		#');

		$sql = $this->init_obj('Avito')->init_obj('db')->db->query('
			select id,url,modered from obs where is_delete = 0
		');

		while($r = $sql->fetch_object())
		{
			#$item = $this->Avito->item($r->url, 1, 1, 1);
			$item = $this->Avito->item($url, 1, 1, 1);
			if(!$item) break;
			else if(is_string($item) or empty($item->id))
			{
				$this->db->update('obs',['is_delete'=>1],['id'=>$r->id]);
			}else{
				$item->url = $r->url;
				#if(isset($item->phone) && !empty($item->imgUrl))
				#{
				#	$format = explode('.',basename($item->imgUrl));
				#	$format = $format[count($format)-1];
				#	sleep(1);
				#	file_put_contents(g::get('imgPath').$r->id.'.'.$format,$this->Avito->get_curl(['url'=>$item->imgUrl]));
				#	//exit;
				#}

				if($this->dbB->chechAvito((array)$item))
				{
					$item->modered = 1;
				}
				else
				{
					if($this->dbB->chechStopAgent((array)$item))
						$item->modered = 1;
				}		

				print_r($item);die();

				$this->db->update('obs',(array) $item,['id'=>$r->id]);
				
			}
			sleep(1);
		}
		echo "Ok\n";
	}
	
	/*
	function indexOld()
	{ //Обходим все активные фильтры и запускаем

		$is_stop = function($str)
		{
			static $arr;
			if(!$arr)
			{
				$arr = explode(',',file_get_contents(g::get('stoppens')));
				foreach($arr as $i=>$v)
					$arr[$i] = trim($v);
			}
			foreach($arr as $v)
				if(!empty($v))
					if(strstr(mb_strtolower($str,'UTF-8'),$v)) return true;
			return false;
		};
		$sql = $this->init_obj('Avito')
			->init_obj('db')
			->db->query('select id,url from filters where status = 1'); // and time <= '.(time()-1*24*60*60)
		//$is_stop('');
		$minTime = time()-2*24*60*60;
		while($r = $sql->fetch_object())
		{
			while(true)
			{
				#$r->url = "/sankt-peterburg/kvartiry/sdam/na_dlitelnyy_srok?pmax=50000&pmin=15000&user=1&p=5&f=550_5702-5703-5704-5705";
				$list = $this->Avito->items($r->url);
				#print_r($list);die();
				if(!$list or empty($list->items)) break 1;
				
				foreach($list->items as $k => $href)
				{
					sleep(2);
					$item = $this->Avito->item($href);
					if($this->db->query('select id from obs where url = "'.$href.'"')->num_rows > 0) continue 1;
					
					
					if(!$item) break 1;
					if(is_string($item)) continue 1;
					
					//var_dump($item); exit;

					if($is_stop($item->description)) continue 1; 
					$item->url = $href;

					$this->db->insert('obs',(array) $item);

				}
				if(!empty($item) && is_object($item) && $minTime > $item->date) break 1;
				sleep(2);
				if(!$list->next) break 1;
				$r->url = $list->next;

			}
			//$this->db->update('filters',['time'=>time()],['id'=>$r->id]);
		}
		_log::set('Ok');
	}
	*/
	
	function index()
	{ //Обходим все активные фильтры и запускаем

		
		$sql = $this->init_obj('Avito')
			->init_obj('db')
			->db->query('select id,url from filters where status = 1'); // and time <= '.(time()-1*24*60*60)
		//$is_stop('');
		$minTime = time()-2*24*60*60;
		while($r = $sql->fetch_object())
		{
			while(true)
			{
				#echo $this->Avito->countStop . "\r\n";
				$list = $this->Avito->items($r->url);
				#$this->Avito->countStop = 0;

				if(!$list or empty($list->items)) break 1;
				
				foreach($list->items as $k => $href)
				{
					#if($k == 3)
					#	die('end');

					$str = 'ps aux | grep "cron.index.' . trim($href) . '"';
					exec($str, $ret);

					if(preg_match('#php\s+\-f#ui', $ret[0]))
					{
						continue;
					}
					else
					{
						sleep(mt_rand(5,7));
						$str = "php -f /var/www/avito/mrtin/data/www/core/index.php cron.indexMany." . $href . " > /dev/null &";
						exec($str);
					}
					
					/*
					#sleep(2);

					if($this->db->query('select id from obs where url = "'.$href.'"')->num_rows > 0) continue 1;
					#echo $this->Avito->countStop . "\r\n";
					$item = $this->Avito->item($href);
					#echo $this->Avito->countStop . "\r\n";
					#if($this->Avito->countStop == 3)
					#	continue 1;
					
					if(!$item) break 1;
					if(is_string($item)) continue 1;
					
					//var_dump($item); exit;

					if($is_stop($item->description)) continue 1; 
					$item->url = $href;

					$this->db->insert('obs',(array) $item);
					*/
				}
				#if(!empty($item) && is_object($item) && $minTime > $item->date) break 1;
				#sleep(2);
				if(!$list->next) break 1;
				$r->url = $list->next;
			}
			//$this->db->update('filters',['time'=>time()],['id'=>$r->id]);
		}
		_log::set('Ok');
	}

	function indexMany($url)
	{ 
		sleep(mt_rand(5,7));

		$is_stop = function($str)
		{
			static $arr;
			if(!$arr)
			{
				$arr = explode(',',file_get_contents(g::get('stoppens')));
				foreach($arr as $i=>$v)
					$arr[$i] = trim($v);
			}
			foreach($arr as $v)
				if(!empty($v))
					if(strstr(mb_strtolower($str,'UTF-8'),$v)) return true;
			return false;
		};

		$this->init_obj('Avito')->init_obj('db');

		if($this->db->query('select id from obs where url = "'.$url.'"')->num_rows > 0) 
			return null;

		$item = $this->Avito->item($url);

		if(!$item)
			return null;

		if(is_string($item))
			return null;
		
		if($is_stop($item->description))
			return null;
		
		$item->url = $url;
		
		if(!empty($item->city))
			$this->db->insert('obs',(array) $item);
	}

}
?>
