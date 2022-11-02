<?php 
namespace App\Libraries;

use Admin\Menu\Models\MenuModel;

class Menu {

    public function __construct()
    {
        $this->user=service('user');
    }

    public function nav_menu($data = array()) {
        $menu_model = new MenuModel();

        $menu = (array)$menu_model->getMenus($data);
        $user=service('user');
        $arg = $data;

        if($data['theme_location']=="admin"){
            $menus = $this->get_admin_menu_nested($menu);

            $nav=$this->create_admin_nav($menus,$arg);
        }else{
            $menus = $this->get_menu_nested($menu);
            $nav=$this->create_nav($menus, $arg);
        }
        return $nav;
    }



    public function get_menu_nested(array $elements, $parentId = 0){

        $tree = array();

        foreach ($elements as $element) {
            if ((string)$element->parent_id  === (string)$parentId) {
                $sub = $this->get_menu_nested($elements, $element->id);
                if ($sub) {
                    $element->sub = $sub;
                }
                $tree[] = $element;
            }
        }

        return $tree;

    }


    public function get_admin_menu_nested($dataset) {

        $sessionPermission=$this->user->getPermissions();
        $tree = array();

        foreach ($dataset as $id=>&$node) {
            //printr($node);
            if($node->url == '#' || ($this->user->getGroupId()==1) || (isset($sessionPermission[$node->url]) && $sessionPermission[$node->url] != "no") ) {

                if ($node->parent_id == 0) {
                    $tree[$id]=&$node;
                } else {
                    if (!isset($dataset[$node->parent_id]->sub)) {
                        $dataset[$node->parent_id]->sub = array();
                    }

                    $dataset[$node->parent_id]->sub[$id] = &$node;
                }
            }
        }
        return $tree;
    }

    public function get_admin_menu_nested_old(array $elements, $parentId = 0){
        $sessionPermission=$this->user->getPermissions();
        //printr($sessionPermission);
        //exit;
        $tree = array();

        foreach ($elements as $element) {
            if($element->url == '#' || (isset($sessionPermission[$element->url]) && $sessionPermission[$element->url] != "no") ) {

                if ((string)$element->parent_id === (string)$parentId) {

                    $sub = $this->get_menu_nested($elements, $element->id);
                    if ($sub) {
                        $element->sub = $sub;
                    }
                    $tree[] = $element;
                }
            }
        }

        return $tree;

    }


    public function create_nav($nav, $arg, $depth = 1){
        if ($arg['theme_location'] == "admin") {
            $url = 'admin_url';
        } else {
            $url = 'base_url';
        }
        if (isset($arg['menu_class']) && $depth == 1) {
            $list_item = '<ul class="' . $arg['menu_class'] . '">';
        } else {
            $list_item = '<ul class="dropdown-menu">';
        }
        foreach ($nav as $item) {
            $item->url = trim($item->url, '/');
//            $list_item .= '<li' . ((isset($item->menu_id)) ? ' id="' . $item->menu_id . '"' : '') . '>';
            $list_item .= '<li' . ((!empty($item->sub)) ? ' class="dropdown"' : '') . '>';

            $class = '';
            if($depth == 1){
                $class = 'nav-item nav-link';
            }
            if(strpos($item->url,'http',0)===false) {
                $href = $url($item->url);
            } else {
                $href = $item->url;
            }
            $data_toggle = '';
            if(!empty($item->sub)){
                $data_toggle = ' data-toggle="dropdown"';
            }
            $list_item .= '<a'.$data_toggle.' href="' . $href . '"' . ' class="'.$class.'"' . '> <i class="fa ' . $item->icon_class . '"></i> ' . $item->title . '</a>';

            if (!empty($item->sub)) {
                $list_item .= $this->create_nav($item->sub, $arg, $depth + 1);
            }
            $list_item .= '</li>';
        }
        $list_item .= '</ul>';
        return $list_item;
    }

    public function create_admin_nav($nav, $arg, $depth = 1)
    {
        if (isset($arg['menu_class']) && $depth == 1) {
            $list_item = '<ul class="' . $arg['menu_class'] . '">';
        } else {

            $list_item = '<ul>';
        }
        foreach ($nav as $item) {
            $f = 0;
            if ( isset($item->sub) ) {
                $f = 1;
            }


            if ( $this->user->hasPermission($item->url) || ( $item->url == '#' && $f ) ) {
               // echo $item->title;
                if ( $f && count($item->sub) == 1 ) {
                    $f    = 0;
                    $item = current($item->sub);
                }
                //printr($item);
                $item->url = trim($item->url, '/');

                $list_item .= '<li' . ((!empty($item->sub)) ? ' class="dropdown"' : ' class="'.$item->class.'"') . '>';

                $class = '';
                if ($depth == 1 && isset($item->sub)) {
                    $class = 'nav-submenu';
                }
                if (strpos($item->url, 'http', 0) === false) {
                    $href = admin_url($item->url);
                } else {
                    $href = $item->url;
                }
                $data_toggle = '';
                if (!empty($item->sub)) {
                    $data_toggle = ' data-toggle="nav-submenu"';
                }

                if($item->url!="*") {
					$target=$item->target?'_blank':'';
                    $list_item .= '<a' . $data_toggle . ' href="' . $href . '"' . ' class="' . $class . '"' . ' target="' . $target . '"'.'> <i class="fa ' . $item->icon_class . '"></i> ' . $item->title . '</a>';
                }else{
                    $list_item.='<span class="sidebar-mini-hidden">'.$item->title.'</span>';
                }

                if (!empty($item->sub) && $f) {
                    $list_item .= $this->create_admin_nav($item->sub, $arg, $depth + 1);
                }
                $list_item .= '</li>';
            }
        }
        $list_item .= '</ul>';
        return $list_item;
    }

    public function list_menu_nav($list, $depth = 1)
    {
        $nav = '<ul class="dd-list">';

        foreach($list as $Item){
            $nav .=	'<li class="dd-item" data-id="'.$Item->id.'">';
            $nav .=		'<div class="dd-handle">';
            $nav .=			'<div class="title float-left">'.($Item->title?:"&nbsp;") .'</div>';
            $nav .=			'<div class="float-right">'.$Item->menu_type."&nbsp;";
            $nav .=				'<a data-toggle="collapse" href="#collapse'.$Item->id.'" title="Edit Menu" aria-expanded="false" aria-controls="collapse'.$Item->id.'"><i class="fa fa-angle-down"></i></a>';
            $nav .=			'</div>';
            $nav .=		'</div>';
            $nav .=		'<div class="collapse" id="collapse'.$Item->id.'">';
            $nav .=			'<div class="block block-content bg-gray-light">';
            $nav .=			    $this->editform($Item);
            $nav .=				'<a href="javacript:void(0)" class="delete-menu" title="Delete Menu">Remove</a>';
            $nav .=			 '</div>';
            $nav .=		'</div>';
            if ( ! empty($Item->sub)){
                $nav .= $this->list_menu_nav($Item->sub, $depth + 1);
            }
            $nav .= '</li>';
        }
        $nav .= '</ul>';
        return $nav;
    }

    public function editform($item){
        $html='<input type="hidden" name="item['.$item->id.'][item_id]" value="'.$item->item_id.'">';

        if($item->menu_type == "custom"){
            $html.='<div class="form-group">
					<label for="slug">URL</label>
					<input type="text" name="item['.$item->id.'][url]" value="'.$item->url.'" class="form-control" placeholder="URL">		
				</div>';
        }else{
            $html.='<input type="hidden" name="item['.$item->id.'][url]" value="'.$item->url.'">';
        }
        $html.='<div class="form-group">
					<label for="slug">Navigation Label</label>
					<input type="text" name="item['.$item->id.'][title]" value="'.$item->title.'" class="form-control" placeholder="Label">		
				</div>
				
				<div class="form-group">
					<label for="slug">CSS Class</label>
					<input type="text" name="item['.$item->id.'][class]" value="'.$item->class.'" class="form-control" placeholder="Class">		
				</div>
				<div class="form-group">
					<div class="checkbox">
						 <input type="checkbox" name="item['.$item->id.'][target]" value="true" '.($item->target ? "checked='checked'":"").'>
						 <label for="checkbox1">Open link in a new tab</label>
					</div>
				</div>';


        return $html;

    }
}
return  __NAMESPACE__ ."\Menu";
/* End of file templates.php */
/* Location: ./application/modules/templates/controllers/templates.php */
