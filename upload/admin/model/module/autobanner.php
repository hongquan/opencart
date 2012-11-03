<?php
class ModelModuleAutoBanner extends Model {
	const TABLE_NAME = 'banner_to_products';
	public function install() {
		$this->uninstall();

		$sql = "CREATE TABLE `".DB_PREFIX.self::TABLE_NAME."`
		   (`banner_id` INT NOT NULL UNIQUE, `product_ids` TEXT NOT NULL,
		   FOREIGN KEY (`banner_id`) REFERENCES `".DB_PREFIX.self::TABLE_NAME.
		   "`(`banner_id`))
		   ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
		$this->db->query($sql);
	}

	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS `".DB_PREFIX.self::TABLE_NAME."`");
	}

	public function getBannerIds() {
		$sql = "SELECT `banner_id` FROM `".DB_PREFIX.self::TABLE_NAME."`";
		$query = $this->db->query($sql);
		return array_map(array($this, '_row_to_id'), $query->rows);
	}

	public function getProductIds($banner_id) {
		if (empty($banner_id)) return FALSE;
		$sql = "SELECT `product_ids` FROM `".DB_PREFIX.self::TABLE_NAME."`
		        WHERE `banner_id` = ".(int)$banner_id;
		$query = $this->db->query($sql);
		$row = $query->row;
		return unserialize($row['product_ids']);
	}

	public function getProductsByIds($product_ids, $include = TRUE) {
		if (!is_array($product_ids)) {
			return FALSE;
		}

		$prdlist = join(', ', $product_ids);

		$sql = "SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)";
		$sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if ($include) {
			$sql .= " AND p.product_id IN ($prdlist)";
		}
		else {
			$sql .= " AND p.product_id NOT IN ($prdlist)";
		}
		
		$sql .= " AND p.status = '1'";
		$sql .= " GROUP BY p.product_id";

		$query = $this->db->query($sql);
		return $query->rows;
	}

	public function addBanner($banner_id, $product_ids) {
		if (!is_array($product_ids)) {
			return FALSE;
		}
		$products = serialize($product_ids);
		$banner_id = (int)$banner_id;
		$sql = "INSERT INTO `".DB_PREFIX.self::TABLE_NAME."`
		        SET `banner_id` = $banner_id, `product_ids` = '$products'";
		return $this->db->query($sql);
	}

	public function editBanner($banner_id, $product_ids) {
		if (!is_array($product_ids)) {
			return FALSE;
		}
		$products = serialize($product_ids);
		$banner_id = (int)$banner_id;
		$sql = "UPDATE `".DB_PREFIX.self::TABLE_NAME."`
		        SET `product_ids` = '$products'
				WHERE `banner_id` = $banner_id";
		return $this->db->query($sql);
	}

	function _row_to_id($row) {
		return $row['banner_id'];
	}
}
?>