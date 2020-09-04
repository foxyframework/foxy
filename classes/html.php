<?php
/**
 * @version     1.0.0 Foxy Framework $
 * @package     Foxy Framework
 * @copyright   Copyright © 2014 - All rights reserved.
 * @license	    GNU/GPL
 * @author	    Foy Team
 * @website	    https://foxyframework.github.io/foxysite/
 *
*/

defined('_Foxy') or die ('restricted access');

class Html
{
    /**
     * Method to render a complete table
     * @param string $id the table id to instantiate datatables via js
     * @param int $key the table primary key
     * @param object $data The result from database
     * @param array $fields group of fields with formats
     * @param array $columns The table columns to display as thead
     * @return string
    */
    public static function renderTable($id, $key, $data, $fields=array(), $columns=array())
    {
        $view  = application::getVar('view');
        $model = application::getModel($view);

        $html  = '';
        $html .= '<div class="w-100 text-right">'.self::renderButtons($view).'</div>';
        $html .= '<form name="tableForm" id="tableForm" action="" method="post">';
        $html .= '<div class="w-100">'.self::renderFilters($view).'</div>';
        $html .= '<div class="table-responsive">';
        $html .= '<table id="'.$id.'" class="table table-hover table-bordered">';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th width="1%" data-orderable="false"><input type="checkbox" id="selectAll"></th>';
        foreach($columns as $column) {
            $html .= '<th>'.$column.'</th>';
        }
        $html .= '<th>#</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        foreach($data as $d) {
            $html .= '<tr>';
            $html .= '<td>';
			$html .= '<input type="checkbox" class="tableCheck" name="cd" data-id="'.$d->{$key}.'">';
			$html .= '</td>';
            foreach($fields as $field) {
                if(is_array($field)) {
                    foreach($field as $k => $v) {
                        //example format $fields = array('myfield' => array('field' => 'myfield', 'format' => 'link')
                        if($k == 'field') { $field = $v; }
                        if($k == 'format' && $v == 'date') { $field = date('d-m-Y', strtotime($d->{$field})); }
                        if($k == 'format' && $v == 'price') { $field = number_format($d->{$field}, 2, '.', ',').'&euro;'; }
                        if($k == 'format' && $v == 'bool') { if($d->{$field} == 1) { $field = 'Sí'; } else { $field = 'No'; } }
                        if($k == 'format' && $v == 'link') { $field = '<a href="'.url::genUrl('index.php?view='.$view.'&layout=admin&id='.$d->{$key}).'">'.$d->{$field}.'</a>'; }
                    }    
                } else {
                    $field = $d->{$field};
                }
                $html .= '<td>'.$field.'</td>';
            }
            $html .= '<td>';
            $html .= '<a href="index.php?task='.$view.'.removeItem&id='.$d->{$key}.'" class="btn btn-danger"><i class="fa fa-trash"></i></a>&nbsp;';
            $html .= '<a href="#" data-toggle="modal" data-target="#editable'.$d->{$key}.'" class="btn btn-success"><i class="fa fa-edit"></i></a>&nbsp;';
            $html .= '<a href="#" data-id="'.$d->{$key}.'" data-view="'.$view.'" data-ordering="'.$d->ordering.'" class="btn btn-info handle"><i class="fa fa-bars"></i></a>';
            $html .= '<div class="modal fade" id="editable'.$d->{$key}.'" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">';
            $html .= '<div class="modal-dialog modal-xl">';
            $html .= '<div class="modal-content">';
            $html .= '<div class="modal-header">';
            $html .= '<h5 class="modal-title" id="exampleModalLabel">Edit</h5>';
            $html .= '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
            $html .= '<span aria-hidden="true">&times;</span>';
            $html .= '</button>';
            $html .= '</div>';
            $html .= '<div class="modal-body">';
            $html .= application::renderView($view, 'edit', array('id' => $d->{$key}));
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</td>';
            $html .= '</tr>';
        }
        $html .= '</tbody>';
        $html .= '</table>';
        $html .= $model->pagination($_GET);
        $html .= '<script>document.addEventListener("DOMContentLoaded", function() { var dataTable = new DataTable(document.querySelector("#'.$id.'"), { layout: { top: "", bottom: "" }, columns: [{ select: [0], sortable: false }]}); });</script>';
        $html .= '</div>';
        $html .= '</form>';

        return $html;
    }

    /**
     * Method to render a group of filters from an xml file
     * @param string $form the form xml name
     * @param string $view the page view name
     * @return string
    */
    public static function renderFilters($view)
    {
        $form = FOXY_COMPONENT.DS.'forms'.DS.'filters_'.$view.'.xml';
        $fields = simplexml_load_file($form);
        
        if(file_exists($form) && is_readable($form )) {
            $html   = '<div class="row row-cols-md-auto g-3 align-items-center my-3">';
            $html  .= '<input type="hidden" name="view" value="'.$view.'">';
        }

        $i = 0;
        foreach($fields as $field) {

            if($field->getName() == "field"){

                if($field[$i]->type == 'text') {

                    $field_name = (string)$field[$i]->name;
                    $html .= html::getTextField($form, $field_name, $_GET[''.$field_name.'']);
                }
                if($field[$i]->type == 'date' || $field[$i]->type == 'calendar') {

                    $field_name = (string)$field[$i]->name;
                    $html .= html::getDateField($form, $field_name, $_GET[''.$field_name.'']);
                }
                if($field[$i]->type == 'users') {

                    $field_name = (string)$field[$i]->name;
                    $html .= html::getUsersField($form, $field_name, $_GET[''.$field_name.'']);
                }
                if($field[$i]->type == 'list') {

                    $field_name = (string)$field[$i]->name;
                    $html .= html::getListField($form, $field_name, $_GET[''.$field_name.'']);
                }

            }
        	$i++;
        }

        if(file_exists($form) && is_readable($form )) {
            $html .= '&nbsp;<button class="btn btn-success mb-3" type="submit">'.language::get('FOXY_SEARCH').'</button>';
            $html .= '</div>';
        }

        return $html;
    }

    /**
     * Method to render a group of buttons from an xml file
     * @param string $form the form xml name
     * @param string $view the page view name
     * @return string
    */
    public static function renderButtons($view)
    {
        $form = FOXY_COMPONENT.DS.'forms'.DS.'filters_'.$view.'.xml';
        $fields = simplexml_load_file($form);
        $html = "";
        $i = 0;

        foreach($fields as $field) {
            if($field->getName() == "button"){
                $field[$i]->icon == "" ? $icon = "" : $icon = "<i class='fa ". $field[$i]->icon. "'></i>&nbsp;";
                $field[$i]->view == "" ? $view = "" : $view = "data-view='". $field[$i]->view. "'";
                $color = isset($field[$i]->color) ? $field[$i]->color : 'success';

                if($field[$i]->modal == true) { $modal = 'href="#" data-toggle="modal" data-target="#'. $field[$i]->id .'"'; } else { $modal = 'href="'. $field[$i]->href .'" id="'. $field[$i]->id .'"'; }
                $html .= '&nbsp;<a '.$modal.' '.$view.'  class="btn btn-' . $color . ' ' . $field[$i]->class . '" >' . $icon . $field[$i]->label . '</a>';
                if($field[$i]->modal == true) {
                    $html .= '<div class="modal fade" id="'. $field[$i]->id .'" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">';
                    $html .= '<div class="modal-dialog modal-xl">';
                    $html .= '<div class="modal-content">';
                    $html .= '<div class="modal-header">';
                    $html .= '<h5 class="modal-title" id="exampleModalLabel">'.$field[$i]->modal_title.'</h5>';
                    $html .= '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
                    $html .= '<span aria-hidden="true">&times;</span>';
                    $html .= '</button>';
                    $html .= '</div>';
                    $html .= '<div class="modal-body">';
                    $html .= application::renderView($field[$i]->page, $field[$i]->layout);
                    $html .= '</div>';
                    $html .= '</div>';
                    $html .= '</div>';
                    $html .= '</div>';
                }
            }

        	$i++;
        }

        return $html;
    }

    /**
     * Method to render a block form
     * @param string $form the form name
     * @return string
    */
    public static function renderBlockForm($id) 
    {
        database::query('SELECT * FROM `#_blocks` WHERE id = '.$id);
        $row = database::fetchObject();

        $block  = strtolower($row->title);
        $params = json_decode($row->params);

        $form   = FOXY_BASE.DS.'blocks'.DS.$block.DS.$block.'.xml';
        $fields = simplexml_load_file($form);
        $html = '';

        $html .= '<form name="blockForm" id="blockForm" method="post" action="index.php?task=blocks.saveBlockItem">';		
        $html .= '<input type="hidden" name="id" value="'.$id.'">';

        $i = 0;
        foreach($fields as $field) {

            if($field[$i]->type == 'text') {

                $field_name = (string)$field[$i]->name;
                $html .= html::getTextField($form, $field_name, $params->{$field_name});
            }
            if($field[$i]->type == 'date' || $field[$i]->type == 'calendar') {

                $field_name = (string)$field[$i]->name;
                $html .= html::getDateField($form, $field_name, $params->{$field_name});
            }
            if($field[$i]->type == 'users') {

                $field_name = (string)$field[$i]->name;
                $html .= html::getUsersField($form, $field_name, $params->{$field_name});
            }
            if($field[$i]->type == 'list') {

                $field_name = (string)$field[$i]->name;
                $html .= html::getListField($form, $field_name, $params->{$field_name});
            }
            if($field[$i]->type == 'media') {

                $field_name = (string)$field[$i]->name;
                $html .= html::getMediaField($form, $field_name, $field[$i]->folder, $params->{$field_name});
            }
            if($field[$i]->type == 'textarea') {

                $field_name = (string)$field[$i]->name;
                $html .= html::getTextareaField($form, $field_name, $params->{$field_name});
            }

            $i++;
        }

        $html .= '<div class="form-group"><button class="btn btn-success" type="submit">'.language::get('FOXY_SEARCH').'</button></div>';
        $html .= '</form>';


        return $html;
    }

    /**
     * Method to render a input box
     * @param string $form the form name
     * @param string $name the field name
     * @param mixed $default optional default value
     * @return string $html a complete input field html
     * @return string
    */
    public static function getTextField($form, $name, $default='')
    {
        $fields = simplexml_load_file($form);
        $html = "";

        foreach($fields as $field) {
            //text inputs...
            if($field['name'] == $name) {
				$field[0]->readonly == 'true' ? $readonly = "readonly='true'" : $readonly = "";
                $field[0]->disabled == 'true' ? $disabled = "disabled='disabled'" : $disabled = "";
                $field[0]->required == 'true' ? $required = "required='true'" : $required = "";
                $field[0]->onchange != "" ? $onchange = 'onchange="'.$field[0]->onchange.'"' : $onchange = "";
                $field[0]->onkeyup != "" ? $onkeyup = " onkeyup='".$field[0]->onkeyup."'" : $onkeyup = "";
                if($field[0]->type != 'hidden') $html .= "<div id='".$field[0]->name."-field' class='mb-3'>";
                if($field[0]->type != 'hidden' && $field[0]->label != "") $html .= "<label for='".$field[0]->id."' class='form-label'>".language::get($field[0]->label)."</label>";
                if($field[0]->type != 'hidden' && $field[0]->label != "") $html .= "<div class='controls'>";
                $html .= "<input type='".$field[0]->type."' id='".$field[0]->id."' value='".str_replace("'","&#39;",$default)."' name='".$field[0]->name."'";
                if($field[0]->type != 'hidden') $html .= $disabled." ".$required." ".$onchange." ".$onkeyup." ".$readonly." placeholder='".language::get($field[0]->placeholder)."' class='form-control ".$field[0]->clase."' autocomplete='off'";
                $html .= ">";
                if($field[0]->type != 'hidden') $html .= "<div class='invalid-feedback'>".language::get($field[0]->message)."</div>";
                if($field[0]->type != 'hidden' && $field[0]->label != "") $html .= "</div>";
                if($field[0]->type != 'hidden') $html .= "</div>";
            }
        }
        return $html;
    }

    /**
     * Method to render an email field
     * @param string $form the form name
     * @param string $name the field name
     * @param mixed $default optional default value
     * @return string $html a complete input field html
    */
    public static function getEmailField($form, $name, $default='')
    {
        $fields = simplexml_load_file($form);
        $html = "";

        foreach($fields as $field) {
            //text inputs...
            if($field['name'] == $name) {
                $field[0]->disabled == 'true' ? $disabled = "disabled='disabled'" : $disabled = "";
                $field[0]->required == 'true' ? $required = "required='true'" : $required = "";
                $field[0]->onchange != "" ? $onchange = "onchange='".$field[0]->onchange."'" : $onchange = "";
                $field[0]->onkeyup != "" ? $onkeyup = " onkeyup='".$field[0]->onkeyup."'" : $onkeyup = "";
                $html .= "<div id='".$field[0]->name."-field' class='mb-3'>";
                if($field[0]->label != "") $html .= "<label for='".$field[0]->id."' class='form-label'>".language::get($field[0]->label)."</label>";
                if($field[0]->label != "") $html .= "<div class='controls'>";
                $html .= "<input type='".$field[0]->type."' name='".$field[0]->name."' style='display:none;' />";
                $html .= "<input type='".$field[0]->type."' id='".$field[0]->id."' value='".$default."' name='".$field[0]->name."'";
                $html .= $disabled.' '.$required.' '.$onchange.$onkeyup.' placeholder="'.language::get($field[0]->placeholder).'" class="form-control '.$field[0]->clase.'"  pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" autocomplete="off"';
                if($field[0]->remote != '') { $html .= " data-remote='".$field[0]->remote."'"; }
                $html .= ">";
                $html .= "<div class='invalid-feedback'>".language::get($field[0]->message)."</div>";
                if($field[0]->label != "") $html .= "</div>";
                $html .= "</div>";
            }
        }
        return $html;
    }

    /**
     * Method to render a number input box
     * @param string $form the form name
     * @param string $name the field name
     * @param mixed $default optional default value
     * @return string $html a complete input field html
    */
    public static function getNumberField($form, $name, $default='')
    {
        $fields = simplexml_load_file($form);
        $html = "";

        foreach($fields as $field) {
            //text inputs...
            if($field['name'] == $name) {
				$field[0]->readonly == 'true' ? $readonly = "readonly='true'" : $readonly = "";
                $field[0]->disabled == 'true' ? $disabled = "disabled='disabled'" : $disabled = "";
                $field[0]->onchange != "" ? $onchange = 'onchange="'.$field[0]->onchange.'"' : $onchange = "";
                $field[0]->onkeyup != "" ? $onkeyup = " onkeyup='".$field[0]->onkeyup."'" : $onkeyup = "";
                if($field[0]->type != 'hidden') $html .= "<div id='".$field[0]->name."-field' class='mb-3'>";
                if($field[0]->type != 'hidden' && $field[0]->label != "") $html .= "<label for='".$field[0]->id."' class='form-label'>".language::get($field[0]->label)."</label>";
                if($field[0]->type != 'hidden' && $field[0]->label != "") $html .= "<div class='controls'>";
                $default != '' ? $default = $default : $default = $field[0]->default;
                $html .= "<input type='".$field[0]->type."' id='".$field[0]->id."' value='".$default."' name='".$field[0]->name."' min='".$field[0]->min."' max='".$field[0]->max."' step='".$field[0]->step."'";
                if($field[0]->type != 'hidden') $html .= $disabled." ".$onchange." ".$onkeyup." ".$readonly." placeholder='".language::get($field[0]->placeholder)."' class='form-control ".$field[0]->clase."' autocomplete='off'";
                $html .= ">";
                $html .= "<div class='invalid-feedback'>".language::get($field[0]->message)."</div>";
                if($field[0]->type != 'hidden' && $field[0]->label != "") $html .= "</div>";
                if($field[0]->type != 'hidden') $html .= "</div>";
            }
        }
        return $html;
    }

    /**
     * Method to render a password field
     * @param string $form the form name
     * @param string $name the field name
     * @param mixed $default optional default value
     * @return string $html a complete input field html
    */
    public static function getPasswordField($form, $name, $default='')
    {
        $fields = simplexml_load_file($form);
        $html = "";

        foreach($fields as $field) {
            //text inputs...
            if($field['name'] == $name) {
                $field[0]->disabled == 'true' ? $disabled = "disabled='disabled'" : $disabled = "";
                $field[0]->required == 'true' ? $required = "required='true'" : $required = "";
                $field[0]->onchange != "" ? $onchange = "onchange='".$field[0]->onchange."'" : $onchange = "";
                $field[0]->onkeyup != "" ? $onkeyup = " onkeyup='".$field[0]->onkeyup."'" : $onkeyup = "";
                $html .= "<div id='".$field[0]->name."-field' class='mb-3'>";
                if($field[0]->label != "") $html .= "<label for='".$field[0]->id."' class='form-label'>".language::get($field[0]->label)."</label>";
                if($field[0]->label != "") $html .= "<div class='controls'>";
                $html .= "<input type='".$field[0]->type."' name='".$field[0]->name."' style='display:none;' />";
                $html .= "<input type='".$field[0]->type."' data-minlength='".$field[0]->minlength."' id='".$field[0]->id."' value='".$default."' name='".$field[0]->name."'";
                if($field[0]->match != '') { $html .= "data-match='".$field[0]->match."' "; }
                $html .= $disabled.' '.$required.' '.$onchange.$onkeyup.' placeholder="'.language::get($field[0]->placeholder).'" class="form-control '.$field[0]->clase.'" autocomplete="off"';
                $html .= ">";
                $html .= "<div class='invalid-feedback'>".language::get($field[0]->message)."</div>";
                if($field[0]->label != "") $html .= "</div>";
                $html .= "</div>";
            }
        }
        return $html;
    }

    /**
     * Method to render a input box
     * @param string $form the form name
     * @param string $name the field name
     * @param mixed $default optional default value
     * @return string $html a complete input field html
    */
    public static function getDateField($form, $name, $default='')
    {
        $fields = simplexml_load_file($form);
        $html = "";

        foreach($fields as $field) {
            //text inputs...
            if($field['name'] == $name) {
				$field[0]->readonly == 'true' ? $readonly = "readonly='true'" : $readonly = "";
                $field[0]->disabled == 'true' ? $disabled = "disabled='disabled'" : $disabled = "";
                $field[0]->required == 'true' ? $required = "required='true'" : $required = "";
                $html .= "<div id='".$field[0]->name."-field' class='mb-3'>";
                if($field[0]->label != "") $html .= "<label for='".$field[0]->id."' class='form-label'>".language::get($field[0]->label)."</label>";
                $html .= "<input type='date' id='".$field[0]->id."' value='".$default."' name='".$field[0]->name."'";
                $html .= $disabled." ".$readonly." ".$required." class='form-control' autocomplete='off'>";
                $html .= "<div class='invalid-feedback'>".language::get($field[0]->message)."</div>";
                $html .= "</div>";
            }
        }
        return $html;
    }

    /**
     * Method to render a input box
     * @param string $form the form name
     * @param string $name the field name
     * @param mixed $default optional default value
     * @param bool $editor optional convert the textarea in a editor
     * @return string $html a complete input field html
    */
    public static function getTextareaField($form, $name, $default='')
    {
        $fields = simplexml_load_file($form);
        $html = "";

        foreach($fields as $field) {
            //text inputs...
            if($field['name'] == $name) {
                $field[0]->disabled == 'true' ? $disabled = "disabled='disabled'" : $disabled = "";
                $field[0]->onchange != "" ? $onchange = "onchange='".$field[0]->onchange."'" : $onchange = "";
                $field[0]->required == 'true' ? $required = "required='true'" : $required = "";
                $html .= "<div id='".$field[0]->name."-field' class='mb-3'>";
                if($field[0]->label != "") $html .= "<label for='".$field[0]->id."' class='form-label'>".language::get($field[0]->label)."</label>";
                if($field[0]->label != "") $html .= "<div class='controls'>";
                $html .= "<textarea id='".$field[0]->id."' maxlength='".$field[0]->maxlength."' placeholder='".language::get($field[0]->placeholder)."' name='".$field[0]->name."' rows='".$field[0]->rows."' cols='".$field[0]->cols."' class='form-control' ".$disabled." ".$required." ".$onchange.">".$default."</textarea>";
                $html .= "<div class='invalid-feedback'>".language::get($field[0]->message)."</div>";
                if($field[0]->label != "") $html .= "</div>";
                $html .= "</div>";
            }
        }

        return $html;
    }

    /**
     * Method to render a input box
     * @param string $form the form name
     * @param string $name the field name
     * @param mixed $default optional default value
     * @param bool $editor optional convert the textarea in a editor
     * @return string $html a complete input field html
    */
    public static function getEditorField($form, $name, $default='')
    {
        $fields = simplexml_load_file($form);
        $html = "";

        foreach($fields as $field) {
            //text inputs...
            if($field['name'] == $name) {
                $field[0]->disabled == 'true' ? $disabled = "disabled='disabled'" : $disabled = "";
                $field[0]->onchange != "" ? $onchange = "onchange='".$field[0]->onchange."'" : $onchange = "";
                $field[0]->required == 'true' ? $required = "required='true'" : $required = "";
                if($field[0]->label != "") $html .= "<label for='".$field[0]->id."' class='form-label'>".language::get($field[0]->label)."</label>";
                $html .= "<textarea id='editor' class='editor' name='".$field[0]->name."' maxlength='".$field[0]->maxlength."' placeholder='".language::get($field[0]->placeholder)."' rows='".$field[0]->rows."' cols='".$field[0]->cols."' class='form-control editor' ".$disabled." ".$required." ".$onchange.">".$default."</textarea>";
            }
        }

        return $html;
    }


    /**
     * Method to render a form button
     * @param string $form the form name
     * @param string $name the field name
     * @return string $html a complete input field html
    */
    public static function getButton($form, $name)
    {
        $fields = simplexml_load_file($form);
        $html = "";

        foreach($fields as $field) {
            if($field['name'] == $name) {
                $field[0]->disabled == 'true' ? $disabled = "disabled='disabled'" : $disabled = "";
                $field[0]->onclick != "" ? $onclick = "onclick='".$field[0]->onclick."'" : $onclick = "";
                $field[0]->type == 'submit' ? $type = "type='".$field[0]->type."'" : $type = "";
                $html .= "<button $type id='".$field[0]->id."' ".$disabled." ".$onclick." class='btn btn-".$field[0]->color." ".$field[0]->clase."'>".language::get($field[0]->value)."</button>";
            }
        }
        return $html;
    }

    /**
     * Method to render a usergroups select box
     * @param $form string the form name
     * @param $name string the field name
     * @param $default mixed optional default value
    */
    public static function getUsergroupsField($form, $name, $default='')
    {
        $fields = simplexml_load_file($form);
        $html = "";

        foreach($fields as $field) {
            if($field['name'] == $name) {
                $field[0]->disabled == 'true' ? $disabled = "disabled='disabled'" : $disabled = "";
                $field[0]->onchange != "" ? $onchange = "onchange='".$field[0]->onchange."'" : $onchange = "";
                $field[0]->required == 'true' ? $required = "required='true'" : $required = "";
                $html .= "<div id='".$field[0]->name."-field' class='mb-3'>";
                if($field[0]->label != "") $html .= "<label class='form-label' for='".$field[0]->id."'>".language::get($field[0]->label)."</label>";
                $html .= "<select id='".$field[0]->id."' name='".$field[0]->name."' ".$required." ".$onchange." class='".$class." form-control' ".$disabled.">";

                database::query('SELECT * FROM `#_usergroups`');
                $rows = database::fetchObjectList();

                $html .= "<option value=''>".language::get('FOXY_SELECT_USERGROUP')."</option>";

				foreach($rows as $row) {
					  $default == $row->id ? $selected = "selected='selected'" : $selected = "";
					  $html .= "<option value='".$row->id."' $selected>".$row->usergroup."</option>";
				}

                $html .= "</select>";
                $html .= "<div class='invalid-feedback'>".language::get($field[0]->message)."</div>";
                $html .= "</div>";
            }
        }
        return $html;
    }

    /**
     * Method to render a users select box
     * @param $form string the form name
     * @param $name string the field name
     * @param $default mixed optional default value
    */
    public static function getUsersField($form, $name, $default='')
    {
        $fields = simplexml_load_file($form);
        $html = "";

        foreach($fields as $field) {
            if($field['name'] == $name) {
                $field[0]->disabled == 'true' ? $disabled = "disabled='disabled'" : $disabled = "";
                $field[0]->onchange != "" ? $onchange = "onchange='".$field[0]->onchange."'" : $onchange = "";
                $field[0]->required == 'true' ? $required = "required='true'" : $required = "";
                $html .= "<div id='".$field[0]->name."-field' class='mb-3'>";
                if($field[0]->label != "") $html .= "<label class='form-label' for='".$field[0]->id."'>".language::get($field[0]->label)."</label>";
                $html .= "<select id='".$field[0]->id."' name='".$field[0]->name."' ".$required." ".$onchange." class='".$class." form-select' ".$disabled.">";

                database::query('SELECT id, username FROM `#_users`');
                $rows = database::fetchObjectList();

                $html .= "<option value=''>".language::get('FOXY_SELECT_AN_OPTION')."</option>";

				foreach($rows as $row) {
					  $default == '' ? $default = user::$id : $default = $default;
					  $default == $row->id ? $selected = "selected='selected'" : $selected = "";
					  $html .= "<option value='".$row->id."' $selected>".$row->username."</option>";
				}

                $html .= "</select>";
                $html .= "<div class='invalid-feedback'>".language::get($field[0]->message)."</div>";
                $html .= "</div>";
            }
        }
        return $html;
    }

    /**
     * Method to render a menuitems select box
     * @param $form string the form name
     * @param $name string the field name
     * @param $default mixed optional default value
    */
    public static function getPagesField($form, $name, $default='')
    {
        $fields = simplexml_load_file($form);
        $html = "";

        foreach($fields as $field) {
            if($field['name'] == $name) {
                $field[0]->disabled == 'true' ? $disabled = "disabled='disabled'" : $disabled = "";
                $field[0]->onchange != "" ? $onchange = "onchange='".$field[0]->onchange."'" : $onchange = "";
                $field[0]->required == 'true' ? $required = "required='true'" : $required = "";
                $html .= "<div id='".$field[0]->name."-field' class='mb-3'>";
                if($field[0]->label != "") $html .= "<label class='form-label' for='".$field[0]->id."'>".language::get($field[0]->label)."</label>";
                $html .= "<select id='".$field[0]->id."' name='".$field[0]->name."' ".$required." ".$onchange." class='form-control' ".$disabled.">";

                database::query('SELECT id, title FROM `#_pages`');
                $rows = database::fetchObjectList();

                $html .= "<option value=''>".language::get('FOXY_SELECT_AN_OPTION')."</option>";

				foreach($rows as $row) {
					  $default == '' ? $default = user::$id : $default = $default;
					  $default == $row->id ? $selected = "selected='selected'" : $selected = "";
					  $html .= "<option value='".$row->id."' $selected>".$row->title."</option>";
				}

                $html .= "</select>";
                $html .= "<div class='invalid-feedback'>".language::get($field[0]->message)."</div>";
                $html .= "</div>";
            }
        }
        return $html;
    }

    /**
     * Method to render a select box
     * @param $form string the form name
     * @param $name string the field name
     * @param $default mixed optional default value
     * @param $options array optional array of options
     * @return $html string a complete select field html
    */
    public static function getListField($form, $name, $default='', $options=null, $key='', $value='', $combobox=false)
    {
        $fields = simplexml_load_file($form);
        $html = "";

        foreach($fields as $field) {
            if($field['name'] == $name) {
                $field[0]->disabled == 'true' ? $disabled = "disabled='disabled'" : $disabled = "";
                if(isset($field[0]->button)){
                    $input[0] = "input-group mb-3";
                    $input[1] = "custom-select";
                }else{
                    $input[0] = "mb-3";
                    $input[1] = "";
                }
                $iconButton = isset($field[0]->iconButton) ? $field[0]->iconButton : "fa fa-plus";
                $buttonId = isset($field[0]->buttonId) ? $field[0]->buttonId : "";
                if(isset($field[0]->multiple)){
                    $multiple[0] = "multiple ";
                    $multiple[1] = "[]";
                    $multiple[2] = "custom-select";
                    $multiple[3] = " size='4'";
                }else{
                    $multiple[0] = "";
                    $multiple[1] = "";
                    $multiple[2] = "";
                    $multiple[3] = "";
                }
                $field[0]->onchange != "" ? $onchange = "onchange='".$field[0]->onchange."'" : $onchange = "";
                $field[0]->required == 'true' ? $required = "required='true'" : $required = "";
				$combobox == true ? $class = 'combobox ' : $class = '';
                $html .= "<div id='".$field[0]->name."-field' class='".$input[0]."'>";
                if($field[0]->label != "") $html .= "<label class='form-label' for='".$field[0]->id."'>".language::get($field[0]->label)."</label>";
                $html .= "<select id='".$field[0]->id."' name='".$field[0]->name . $multiple[1] ."' ".$required." ". $onchange . $multiple[0] ." class='custom-select". $class ." ". $input[1] ." ".$field[0]->classe. " ".  $multiple[2] ." form-select' ".$disabled. $multiple[3] .">";

				foreach($field[0]->option as $option) {
					  $default == $option['value'] ? $selected = "selected='selected'" : $selected = "";
					  $option['onclick'] != '' ? $click = "onclick='".$option['onclick']."'" : $click = "";
					  $html .= "<option value='".$option['value']."' $click $selected>".language::get($option[0])."</option>";
				}

				if($options != null) {

					foreach($options as $option) {
						if($key == '' && $value == '') {
							$default == $option->$name ? $selected = "selected='selected'" : $selected = "";
							$html .= "<option value='".$option->$name."' $selected>".$option->$name."</option>";
						} else {
							$default == $option->$value ? $selected = "selected='selected'" : $selected = "";
							$html .= "<option value='".$option->$value."' $selected>".$option->$key."</option>";
						}
					}
                }
                if($field[$i]->query != '') {
                    database::query($field[$i]->query);
                    $options    = database::fetchObjectList();
                    $value      = $field[$i]->value;
                    $key        = $field[$i]->key;
                    foreach($options as $option) {
                        $field[$i]->name == $option->$key ? $selected = "selected='selected'" : $selected = "";
                        $html .= "<option value='".$option->$key."' $selected>".$option->$value."</option>";
                    }
                }
                $html .= "</select>";
                if(isset($field[0]->button)){
                    $html .= "<div class='input-group-append'>
                                <button id='". $buttonId ."' class='btn btn-outline-secondary' type='button'><i class='".$iconButton."' aria-hidden='true'></i></button>
                            </div>";
                }
                $html .= "<div class='invalid-feedback'>".language::get($field[0]->message)."</div>";
                $html .= "</div>";
            }
        }
        return $html;
    }

 	/**
     * Method to render a checkbox
     * @param $form string the form name
     * @param $name string the field name
     * @param $default mixed optional default value
     * @return $html string a complete checkbox field html
    */
    public function getCheckboxField($form, $name, $default='')
    {
        $fields = simplexml_load_file($form);
        $html = "";

        foreach($fields as $field) {
            if($field['name'] == $name) {
                $field[0]->onclick != "" ? $onclick = "onclick='".$field[0]->onclick."'" : $onclick = "";
                $field[0]->required == 'true' ? $required = "required='true'" : $required = "";
                $html .= "<div class='mb-3'>";
                foreach($field[0]->option as $option) {
                    $html .= "<div id='".$field[0]->name."-field' class='form-check-inline'>";
                    $default == $option['value'] ? $checked = "checked='checked';" : $checked = "";
                    $html .= "<input type='checkbox' class='form-check-input' name='".$field[0]->name."' id='".$field[0]->id."' value='".$option['value']."' ".$onclick." ".$required."'>";
                    $html .= "<label class='form-check-label' for='".$field[0]->id."'>".language::get($option[0])."</label>";
                    $html .= "</div>";
                }
                //$html .= "<span id='".$field[0]->name."-msg'></span>";
                $html .= "</div>";
            }
        }
        return $html;
    }

    /**
     * Method to render a radio
     * @param $form string the form name
     * @param $name string the field name
     * @param $default mixed optional default value
     * @return $html string a complete radio field html
    */
    public static function getRadioField($form, $name, $default='')
    {
        $fields = simplexml_load_file($form);
        $html = "";

        foreach($fields as $field) {
            if($field['name'] == $name) {
                $field[0]->onclick != "" ? $onclick = "onclick='".$field[0]->onclick."'" : $onclick = "";
                $field[0]->required == 'true' ? $required = "required='true'" : $required = "";
                $html .= "<div class='mb-3'>";
                foreach($field[0]->option as $option) {
                    $html .= "<div id='".$field[0]->name."-field' class='form-check-inline'>";
                    $default == $option['value'] ? $checked = "checked='checked';" : $checked = "";
                    $html .= "<input type='radio' class='form-check-input' name='".$field[0]->name."' id='".$field[0]->id."' value='".$option['value']."' ".$onclick."  ".$required."'>";
                    $html .= "<label class='form-check-label' for='".$field[0]->id."'>".language::get($option[0])."</label>";
                    $html .= "</div>";
                }
                $html .= "<div class='invalid-feedback'>".language::get($field[0]->message)."</div>";
                $html .= "</div>";
            }
        }
        return $html;
    }

    /**
     * Method to render a filelist field
     * @param $form string the form name
     * @param $name string the field name
     * @param $name string the folder path
     * @param $default mixed optional default value
     * @return $html string a complete filelist field html
    */
    public static function getFilesField($form, $name, $folder, $default='')
    {
        $fields = simplexml_load_file($form);
        $html = "";

		$dir = opendir($folder);
		while (false !== ($file = readdir($dir))) {
			if( $file != "." && $file != "..") {
				$ficheros[] = $file;
			}
		}
		closedir($dir);

        foreach($fields as $field) {
            if($field['name'] == $name) {
                $field[0]->disabled == 'true' ? $disabled = "disabled='disabled'" : $disabled = "";
                $field[0]->onchange != "" ? $onchange = "onchange='".$field[0]->onchange."'" : $onchange = "";
                $field[0]->required == 'true' ? $required = "required='true'" : $required = "";
                $html .= "<div id='".$field[0]->name."-field' class='mb-3'>";
                if($field[0]->label != "") $html .= "<label class='form-label' for='".$field[0]->id."'>".language::get($field[0]->label)."</label>";
                $html .= "<select class='form-control' id='".$field[0]->id."' name='".$field[0]->name."' ".$onchange." ".$disabled." ".$required.">";
                $html .= "<option value=''>".language::get('FOXY_SELECT_AN_OPTION')."</option>";
				foreach($ficheros as $fichero) {
					  $default == $fichero ? $selected = "selected='selected'" : $selected = "";
					  $html .= "<option value='".$fichero."' $selected>".$fichero."</option>";
				}

                $html .= "</select>";
                $html .= "<div class='invalid-feedback'>".language::get($field[0]->message)."</div>";
                $html .= "</div>";
            }
        }
        return $html;
    }

    /**
     * Method to render a folderlist field
     * @param $form string the form name
     * @param $name string the field name
     * @param $name string the folder path
     * @param $default mixed optional default value
     * @return $html string a complete filelist field html
    */
    public static function getFoldersField($form, $name, $directory_path, $default='')
    {
        $fields = simplexml_load_file($form);
        $html = "";

		$sub_directories = array_map('basename', glob($directory_path . '/*', GLOB_ONLYDIR));

        foreach($fields as $field) {
            if($field['name'] == $name) {
                $field[0]->disabled == 'true' ? $disabled = "disabled='disabled'" : $disabled = "";
                $field[0]->onchange != "" ? $onchange = "onchange='".$field[0]->onchange."'" : $onchange = "";
                $field[0]->required == 'true' ? $required = "required='true'" : $required = "";
                $html .= "<div id='".$field[0]->name."-field' class='mb-3'>";
                if($field[0]->label != "") $html .= "<label class='form-label' for='".$field[0]->id."'>".language::get($field[0]->label)."</label>";
                $html .= "<select class='form-control' id='".$field[0]->id."' name='".$field[0]->name."' ".$required." ".$onchange." ".$disabled.">";
                $html .= "<option value=''>".language::get('FOXY_SELECT_AN_OPTION')."</option>";
				foreach($sub_directories as $directory) {
					  $default == $directory ? $selected = "selected='selected'" : $selected = "";
					  $html .= "<option value='".$directory."' $selected>".$directory."</option>";
				}

                $html .= "</select>";
                $html .= "<div class='invalid-feedback'>".language::get($field[0]->message)."</div>";
                $html .= "</div>";
            }
        }
        return $html;
    }

    /**
     * Method to render a folderlist field
     * @param $form string the form name
     * @param $name string the field name
     * @param $name string the folder path
     * @param $default mixed optional default value
     * @return $html string a complete filelist field html
    */
    public static function getMediaField($form, $name, $folder, $default='')
    {
        $fields = simplexml_load_file($form);
        $html = "";
        
        $dir = opendir('assets/img/'.$folder);
		while (false !== ($file = readdir($dir))) {
			if( $file != "." && $file != "..") {
				$ficheros[] = $file;
			}
		}
		closedir($dir);

        foreach($fields as $field) {
            if($field['name'] == $name) {

                $html .= "<label for='".$field[0]->id."' class='form-label'>".language::get($field[0]->label)."</label>";
                $html .= "<div class='input-group mb-3'>";
                $default != '' ? $value = "value='$default'" : $value = "";
                $html .= "<input type='text' id='".$field[0]->id."' $value class='form-control' aria-describedby='button-addon2'>";
                $content = addslashes("<img width='200' src='document.getElementById("+$field[0]->id+").src;'>");
                $html .= "<button data-toggle='popover' title=Preview' data-html='true' data-content='' data-placement='left' class='btn btn-outline-secondary' type='button'>Preview</button>";
                $html .= "<button class='btn btn-outline-secondary' type='button' id='button-addon2' data-toggle='modal' data-target='#".$field[0]->id."Modal'>Select</button>";
                $html .= "</div>";

                $html .= "<div class='modal' id='".$field[0]->id."Modal' tabindex='-1'>";
                $html .= "<div class='modal-dialog modal-xl'>";
                $html .= "<div class='modal-content'>";
                $html .= "<div class='modal-header'>";
                $html .= "<h5 class='modal-title'>Select image</h5>";
                $html .= "<button type='button' class='close closeImageModal' data-id='".$field[0]->id."Modal' aria-label='Close'>";
                $html .= "<span aria-hidden='true'>&times;</span>";
                $html .= "</button>";
                $html .= "</div>";
                $html .= "<div class='modal-body'>";
                $html .= "<div class='d-flex flex-row'>";

                foreach($ficheros as $fichero) {
                    $html .= "<img class='img-selector' data-id='".$field[0]->id."' src='assets/img/".$folder."/".$fichero."' alt='...'>";
                }
                 
                $html .= "</div>";
                $html .= "</div>";
                $html .= "</div>";
                $html .= "</div>";
                $html .= "</div>";

                
            }
        }
        return $html;
    }
}
