<?php  //  -*- mode:php; tab-width:2; c-basic-offset:2; -*-

namespace mod\arkeogis;

class Ajax {

	public static function showthemap($search) {
		$search=$_REQUEST; // override the $search wich is fucked, I don't really know why

		\core\Core::log($search);

		$addtable=array('ark_site_period' => false,
										'ark_siteperiod_production' => false,
										'ark_siteperiod_furniture' => false,
										'ark_siteperiod_realestate' => false);

		$query=' WHERE (1=1) ';
		$args=array();

		if (isset($search['db_include']) && count($search['db_include'])) {
			$query.=' AND si_database_id IN (?)';
			$args[]=$search['db_include'];
		}

		if (isset($search['db_exclude']) && count($search['db_exclude'])) {
			$query.=' AND si_database_id NOT IN (?)';
			$args[]=$search['db_exclude'];
		}

		if (isset($search['period_include']) && count($search['period_include'])) {
			$addtable['ark_site_period']=true;
			foreach($search['period_include'] as $period) {
				$query.=' AND sp_period_start >= ? AND sp_period_start <= ?';
				$args[]=$period;
				$args[]=$period;
			}
		}

		if (isset($search['period_exclude']) && count($search['period_exclude'])) {
			$addtable['ark_site_period']=true;
			foreach($search['period_include'] as $period) {
				$query.=' AND NOT (sp_period_start >= ? AND sp_period_start <= ?)';
				$args[]=$period;
				$args[]=$period;
			}
		}

		if (isset($search['centroid'])) {
			foreach($search['centroid'] as $v) {
				$query.=' AND si_centroid=?';
				$args[]=(int)$v;
			}
		}

		if (isset($search['knowledge'])) {
			$addtable['ark_site_period']=true;
			$query.=' AND sp_knowledge_type IN(?)';
			$args[]=$search['knowledge'];
		}

		if (isset($search['occupation']) && count($search['occupation'])) {
			foreach($search['occupation'] as $v) {
				$query.=' AND si_occupation=?';
				$args[]=$v;
			}
		}

		if (isset($search['production_include']) && count($search['production_include'])) {
			$addtable['ark_site_period']=true;
			$addtable['ark_siteperiod_production']=true;
			$query.=' AND sp_site_period_id IN (?)';
			$args[]=$search['production_include'];
		}

		if (isset($search['production_exclude']) && count($search['production_exclude'])) {
			$addtable['ark_site_period']=true;
			$addtable['ark_siteperiod_production']=true;
			$query.=' AND sp_site_period_id NOT IN (?)';
			$args[]=$search['production_include'];
		}

		if (isset($search['furniture_include']) && count($search['furniture_include'])) {
			$addtable['ark_site_period']=true;
			$addtable['ark_siteperiod_furniture']=true;
			$query.=' AND sf_id IN (?)';
			$args[]=$search['furniture_include'];
		}

		if (isset($search['furniture_exclude']) && count($search['furniture_exclude'])) {
			$addtable['ark_site_period']=true;
			$addtable['ark_siteperiod_furniture']=true;
			$query.=' AND sf_id NOT IN (?)';
			$args[]=$search['furniture_include'];
		}

		if (isset($search['realestate_include']) && count($search['realestate_include'])) {
			$addtable['ark_site_period']=true;
			$addtable['ark_siteperiod_realestate']=true;
			$query.=' AND sr_id IN (?)';
			$args[]=$search['realestate_include'];
		}

		if (isset($search['realestate_exclude']) && count($search['realestate_exclude'])) {
			$addtable['ark_site_period']=true;
			$addtable['ark_siteperiod_realestate']=true;
			$query.=' AND sr_id NOT IN (?)';
			$args[]=$search['realestate_include'];
		}


		$select="SELECT si_code, si_name";
		$select.=" FROM ark_site";
		if ($addtable['ark_site_period']) {
			$select.=" LEFT JOIN ark_site_period ON sp_site_code = si_code";
		}
		if ($addtable['ark_siteperiod_production']) {
			$select.=" LEFT JOIN ark_siteperiod_production ON sp_site_period_id = sp_id";
		}
		if ($addtable['ark_siteperiod_furniture']) {
			$select.=" LEFT JOIN ark_siteperiod_furniture ON sf_site_period_id = sp_id";
		}
		if ($addtable['ark_siteperiod_realestate']) {
			$select.=" LEFT JOIN ark_siteperiod_realestate ON sr_site_period_id = sp_id";
		}

		$query=$select.' '.$query;

		//$query.=' GROUP BY si_code';

		\core\Core::log($query);
		$result=\core\Core::$db->fetchAll($query, $args);
		\core\Core::log($result);
		\core\Core::log('result count: '.count($result));
		return $result;
	}
}
