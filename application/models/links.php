<?php

class Links extends CI_Model {

    var $links_tab = 'links';

    function __construct() {
        parent::__construct();
    }

    function get_list($language) {
        $result = array();
        $sql = 'SELECT
					*
				FROM
					`' . $this->db->dbprefix($this->links_tab) . '`
				WHERE
					`deleted` = "N"
				ORDER BY
					`id` DESC';
        $query = $this->db->query($sql, array($language));
        foreach ($query->result() as $row) {
            $result['record_arr'][$row->id] = $row;
        }
        return $result;
    }

    function get($rec_id) {
        $sql = 'SELECT
					*
				FROM
					`' . $this->db->dbprefix($this->links_tab) . '`
				WHERE
					`deleted` = "N"
					AND `id` = ?';
        $query = $this->db->query($sql, array($rec_id));
        return $query->row();
    }

    function update($params) {
        $sql = 'UPDATE
					`' . $this->db->dbprefix($this->links_tab) . '`
				SET
					`temporary` = "N",
					`title` = ?,
                                        `link` = ?

				WHERE
					`id` = ?';
        $this->db->query($sql, $params);
    }

    function delete($rec_id) {
        $sql = 'UPDATE
					`' . $this->db->dbprefix($this->links_tab) . '`
				SET
					`deleted` = "Y"
				WHERE
					`id` = ?';
        $this->db->query($sql, array($rec_id, $rec_id));
    }

    function create_temporary($language) {
        $sql = 'INSERT INTO
							`' . $this->db->dbprefix($this->links_tab) . '`
						SET
							`temporary` = "Y"';
        $this->db->query($sql);
        return $this->db->insert_id();
    }

    function delete_temporary() {
        $sql = 'DELETE FROM
							' . $this->db->dbprefix($this->links_tab) . '
						WHERE
							`temporary` = "Y"';
        $this->db->query($sql);
    }
    
    function getLanding() {
            $sql = 'SELECT
                            *
                    FROM
                            `' . $this->db->dbprefix($this->links_tab) . '`
                    WHERE
                            `deleted` = "N"
                            AND `id` = 41';
            $query = $this->db->query($sql, array());
            return $query->row();
    }

}
