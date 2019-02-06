<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin_links extends MX_Controller {

    var $links_list = array();
    var $user_type = '';
    public function __construct() {
        parent::__construct();
        // nocache
        $this->gclass->nocache();
        // check
        if ($this->session->userdata('admin_user') === FALSE)
            redirect(base_url('admin'), 'refresh');
        if (!in_array('links', explode(',', $this->session->userdata('admin_user')->access)))
            redirect(base_url('admin'), 'refresh');
        // load models
        $this->load->model('Links');
        // load helhers
        $this->load->helper('text');
        // set language
        setTranslationLanguage($this->session->userdata('admin_user')->sys_lang, 'admin');
        // get list
        $this->links_list = $this->Links->get_list($this->session->userdata('admin_user')->content_lang);
        
        $this->user_type = $this->session->userdata('admin_user')->type;
    }

    public function index($rec_id = -1) {
        // set title
        $this->gclass->addTitle(__('Links'));
        // data
        $data = array();
        $data['links_list'] = $this->links_list;
        $data['user_type'] = $this->user_type;
        
        // add Js
        $this->gclass->addJs('/js/tinymce/tinymce.min.js');
        $this->gclass->addJs('http://code.jquery.com/jquery-migrate-1.2.1.min.js');
        $this->gclass->addJs('/js/admin/jquery.uniform.min.js');
        // add Css
        $this->gclass->addCss('/css/admin/uniform.default.css');
        // get record
        if ($rec_id >= 0) {
            if (isset($this->links_list['record_arr'][$rec_id])) {
                // set title
                $this->gclass->addTitle(__('Edit record'));
                // data
                $data['record'] = $this->links_list['record_arr'][$rec_id];
                // set title
                $this->gclass->addTitle($data['record']->title);
            } else {
                // set title
                $this->gclass->addTitle(__('Create new record'));
                // delete and create new
                $this->Links->delete_temporary();
                $tmp_id = $this->Links->create_temporary($this->session->userdata('admin_user')->content_lang);
                $data['record'] = $this->Links->get($tmp_id);
            }
        }
        // meta
        $meta = array(
            'title' => implode(' - ', array_reverse($this->gclass->title_array)),
            'keywords' => implode(', ', $this->gclass->keywords_array),
            'description' => implode(', ', $this->gclass->description_array),
            'css' => $this->gclass->css_array,
            'js' => $this->gclass->js_array
        );
        $this->load->view('header', $meta);
        $this->load->view('admin_links', $data);
        $this->load->view('footer_iframe');
    }

    public function update($rec_id) {
            // data
            $data = array();
            $data['status'] = 'er';
            // validate
            if ($this->input->post('title') == '')
                $data['error']['title'] = 'Please enter title!';
            if ($this->input->post('link') == '')
                $data['error']['link'] = 'Please enter link!';
            
            // update
            if (!isset($data['error'])) {
                $params = array(
                    'title' => $this->input->post('title'),
                    'link' => $this->input->post('link'),
                    'id' => $rec_id
                );
                $this->Links->update($params);
                // alert
                if ($this->db->affected_rows() > 0)
                    $this->session->set_flashdata('alert', array('type' => 'ok', 'msg' => $this->links_list['record_arr'][$rec_id]->temporary == 'Y' ? __('New createded!') : __('Data was updated!')));
                // for out
                if ($this->input->post('close') == 1) {
                    //$data['run'][1] = 'parent.refreshIFrame();';
                    $data['run'][1] = "$('#iframe_links', parent.document).attr('src', '/admin/links');";
                } else {
                    if ($this->links_list['record_arr'][$rec_id]->temporary == 'Y') {
                        $data['run'][1] = "$('#iframe_links', parent.document).attr('src', '/admin/links/" . $rec_id . "');";
                    } else {
                        // status
                        $data['status'] = 'ok';
                    }
                }
            }
            // out
            echo json_encode($data);
    }

    public function position() {
        $data = array();
        $data['cnt'] = 0;
        $ids = $this->input->post('ids');
        if (count(explode(',', $ids)) > 0)
            $data['cnt'] = $this->Links->change_position($ids);
        echo json_encode($data);
    }

    public function menu($rec_id) {
        if (isset($this->links_list['record_arr'][$rec_id])) {
            // change
            $this->Links->change_menu($rec_id, $this->links_list['record_arr'][$rec_id]->menu == 'Y' ? 'N' : 'Y');
            // alert
            $this->session->set_flashdata('alert', array('type' => 'ok', 'msg' => __('Data was updated!')));
            // redirect
            redirect('/admin/categories', 'location');
        }
    }

    public function display($rec_id) {
        if (isset($this->links_list['record_arr'][$rec_id])) {
            // change
            $this->Links->change_display($rec_id, $this->session->userdata('admin_user')->content_lang, $this->links_list['record_arr'][$rec_id]->display == 'Y' ? 'N' : 'Y');
            // alert
            $this->session->set_flashdata('alert', array('type' => 'ok', 'msg' => __('Data was updated!')));
            // redirect
            redirect('/admin/links', 'location');
        }
    }

    public function delete($rec_id) {
        if (isset($this->links_list['record_arr'][$rec_id])) {
            // delete
            $this->Links->delete($rec_id);
            // alert
            $this->session->set_flashdata('alert', array('type' => 'er', 'msg' => __('Deleted!')));
            // redirect
            redirect('/admin/links', 'location');
        }
    }

    public function show_row($data) {
        if ($data->temporary == 'N') {
            ?>
            <tr>
                <td>
                    <?php echo mb_strlen($data->title) > 30 ? mb_substr($data->title, 0, 30) . '...' : $data->title; ?><br/>
                    <?php echo $data->link;?>
                </td>
                <td class="center"><a href="<?php echo base_url('admin/links/' . $data->id) ?>"><img src="/images/admin/sys_edit.gif" border="0" height="17" alt="edit" /></a></td>
                <td class="center"><a href="javascript:void(0);" onclick="confirm2('<?php echo __('Delete') ?>', '<?php echo __('Do you want to delete record') ?> &quot;<?php echo addslashes($data->title); ?>&quot;?', '<?php echo base_url('admin/links/delete/' . $data->id) ?>')"><img src="/images/admin/sys_delete.gif" border="0" height="17" alt="del" /></a></td>
            </tr>
            <?php
        }
    }
    
    public function show_row_user($data) {
        if ($data->temporary == 'N') {
            ?>
            <tr>
                <td style="text-align:center;" >
                    <?php echo $data->title; ?>
                </td>
                <td>
                    <?php echo '<a class="links-url" target="_blank" href="'.($data->link.'/'.$this->session->userdata('admin_user')->traffic_source).'">'.__('Open link').' </a>'?>
                    <?php echo '<input class="white_w width-350  main_input" type="text" value="'.$data->link.'/'.$this->session->userdata('admin_user')->traffic_source.'">'?>
                </td>
            </tr>
            <?php
        }
    }

}
