<?php

if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}

if (!function_exists('str_starts_with')) {
	/**
	 * 判断字符串是否以指定字符串开头
	 * @param string $haystack 
	 * @param string $needle 要在 haystack 中搜索的子串。
	 * @return bool
	 */
	function str_starts_with(string $haystack, string $needle): bool
	{
		return $needle !== '' && strncmp($haystack, $needle, strlen($needle)) === 0;
	}
}
if (!function_exists('str_ends_with')) {
	/**
	 * 判断字符串是否以指定字符串结尾
	 * @param string $haystack 
	 * @param string $needle 要在 haystack 中搜索的子串。
	 * @return bool
	 */
	function str_ends_with(string $haystack, string $needle): bool
	{
		return $needle !== '' && substr($haystack, -strlen($needle)) === (string) $needle;
	}
}

if (!function_exists('str_starts_replace')) {
	/**
	 * 替换字符串开头
	 * @param string $search 要被替换的字符串
	 * @param string $replace 替换的字符串
	 * @param string $subject 被替换的字符串
	 * @return string
	 */
	function str_starts_replace(string $search, string $replace, string $subject)
	{
		if (strpos($subject, $search) === 0) { // 检查$search是否在$string开头
			return substr_replace($subject, $replace, 0, strlen($search));
		}
		return $subject; // 如果$search不在开头，则返回原字符串
	}
}

class Widget_Contents_Hot extends Widget_Abstract_Contents
{
	public function execute()
	{
		$recommend_text = Joe\isMobile() ? Helper::options()->JIndex_Mobile_Recommend : Helper::options()->JIndex_Recommend;
		$recommend = joe\optionMulti($recommend_text, '||', null);
		$JIndexSticky = joe\optionMulti(Helper::options()->JIndexSticky, '||', null);
		$IndexHotHidePost = joe\optionMulti(Helper::options()->IndexHotHidePost, '||', null);
		$hide_contents_cid_list = array_unique(array_merge($recommend, $JIndexSticky, $IndexHotHidePost));
		if (empty($hide_contents_cid_list)) $hide_contents_cid_list = ['empty'];
		$this->parameter->setDefault(array('pageSize' => 10));
		$select = $this->select();
		$select->cleanAttribute('fields');
		$this->db->fetchAll(
			$select->from('table.contents')
				->where('table.contents.cid NOT' . "\r\n" . 'IN?', $hide_contents_cid_list)
				->where("table.contents.password IS NULL OR table.contents.password = ''")
				->where('table.contents.status = ?', 'publish')
				->where('table.contents.created <= ?', time())
				->where('table.contents.type = ?', 'post')
				->limit($this->parameter->pageSize)
				->order('table.contents.views', Typecho_Db::SORT_DESC),
			array($this, 'push')
		);
	}
}

class Widget_Contents_Sort extends Widget_Abstract_Contents
{
	public function execute()
	{
		$this->parameter->setDefault(array('page' => 1, 'pageSize' => 10, 'type' => 'created'));
		$offset = $this->parameter->pageSize * ($this->parameter->page - 1);
		$select = $this->select();
		$select->cleanAttribute('fields');
		$hide_categorize_slug = array_map('trim', explode("||", Helper::options()->JIndex_Hide_Categorize ?? ''));
		if (!empty($hide_categorize_slug)) {
			$categorize_sql = $this->db->select('mid', 'slug')->from('table.metas')->where('table.metas.type = ?', 'category');
			$hide_categorize_id = $this->db->fetchAll($categorize_sql);
			if (is_array($hide_categorize_id) && !empty($hide_categorize_id)) {
				$hide_categorize_list = [];
				foreach ($hide_categorize_id as $key => $value) {
					$hide_categorize_list[$value['mid']] = $value['slug'];
				}
				$hide_categorize_list = array_diff($hide_categorize_list, $hide_categorize_slug);
				$hide_categorize_list = array_values(array_flip($hide_categorize_list));
				$select->join('table.relationships', 'table.contents.cid = table.relationships.cid')
					->where('table.relationships.mid IN ?', $hide_categorize_list)
					->group('table.contents.cid');
			}
		}
		$select->from('table.contents')->where('table.contents.type = ?', 'post')
			->where('table.contents.status = ?', 'publish')
			->where('table.contents.created < ?', time())
			->limit($this->parameter->pageSize)
			->offset($offset)
			->order($this->parameter->type, Typecho_Db::SORT_DESC);
		$this->db->fetchAll($select, array($this, 'push'));
	}
}

class Widget_Contents_Post extends Widget_Abstract_Contents
{
	public function execute()
	{
		$select = $this->select();
		$select->cleanAttribute('fields');
		$this->db->fetchAll(
			$select
				->from('table.contents')
				->where('table.contents.type = ?', 'post')
				->where('table.contents.cid = ?', $this->parameter->cid)
				->limit(1),
			array($this, 'push')
		);
	}
}
