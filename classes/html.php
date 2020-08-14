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
     * Method to load a form
     * @param string $form the form xml name
     * @return object
    */
    public static function getForm($form)
    {
        $output = simplexml_load_file('component/forms/'.$form.'.xml');
        return $output;
    }

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

        $html  = '';
        $html .= '<div class="table-responsive">';
        $html .= '<table id="'.$id.'" class="table table-striped table-bordered">';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th width="1%" data-orderable="false"><input type="checkbox" id="selectAll"></th>';
        foreach($columns as $column) {
            $html .= '<th>'.$column.'</th>';
        }
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        foreach($data as $d) {
            $html .= '<tr class="item" data-id="'.$d->{$key}.'">';
            $html .= '<td>';
			$html .= '<input type="checkbox" name="cd" data-id="'.$d->{$key}.'">';
			$html .= '</td>';
            foreach($fields as $field) {
                if(is_array($field)) {
                    foreach($field as $k => $v) {
                        if($k == 'field') { $field = $v; }
                        if($k == 'format' && $v == 'date') { $field = date('d-m-Y', strtotime($d->{$field})); }
                        if($k == 'format' && $v == 'price') { $field = number_format($d->{$field}, 2, '.', ',').'&euro;'; }
                        if($k == 'format' && $v == 'bool') { if($d->{$field} == 1) { $field = 'Sí'; } else { $field = 'No'; } }
                        if($k == 'format' && $v == 'link') { $field = '<a href="'.url::genUrl('index.php?view='.$view.'&layout=edit&id='.$d->{$key}).'">'.$d->{$field}.'</a>'; }
                    }    
                } else {
                    $field = $d->{$field};
                }
                $html .= '<td>'.$field.'</td>';
            }
            $html .= '</tr>';
        }
        $html .= '</tbody>';
        $html .= '</table>';
        $html .= '<script>$(document).ready(function() { var dataTable = $("#'.$id.'").DataTable({ "order": [[1, "asc"]], "paging": false, rowReorder: true, responsive: { details: false } });';
        $html .= '});</script>';
        $html .= '</div>';

        return $html;
    }

    /**
     * Method to render a group of filters from an xml file
     * @param string $form the form xml name
     * @param string $view the page view name
     * @return string
    */
    public static function renderFilters($form, $view)
    {
        $fields = simplexml_load_file('component/forms/filters_'.$form.'.xml');
        $html   = '<div class="form-inline my-3">';
        $html  .= '<input type="hidden" name="view" value="'.$view.'">';

        $i = 0;
        foreach($fields as $field) {

            if($field->getName() == "field"){
                if($i > 0) { $html .= '&nbsp;'; }

                if($field[$i]->type == 'text') {

                    $field_name = (string)$field[$i]->name;
                    $html .= html::getTextField($form, $field_name, $_GET[''.$field_name.'']);
                }
                if($field[$i]->type == 'date' || $field[$i]->type == 'calendar') {

                    $field_name = (string)$field[$i]->name;
                    $html .= html::getDateField($form, $field_name, $_GET[''.$field_name.'']);
                    $html .= "<script>document.addEventListener('DOMContentLoaded', function(event) { $(function(){ $('#".(string)$field[$i]->id."-icon').datetimepicker({sideBySide: false,format: '".(string)$field[$i]->format."'}); }); });</script>";
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

        $html .= '&nbsp;<button class="btn btn-success" type="submit">'.language::get('FOXY_SEARCH').'</button>';
        $html .= '</div>';

        return $html;
    }

    /**
     * Method to render a group of buttons from an xml file
     * @param string $form the form xml name
     * @param string $view the page view name
     * @return string
    */
    public static function renderButtons($form, $view)
    {
        $fields = simplexml_load_file('component/forms/filters_'.$form.'.xml');
        $html = "";
        $i = 0;

        foreach($fields as $field) {
            if($field->getName() == "button"){
                $field[$i]->icon == "" ? $icon = "" : $icon = "<i class='fa ". $field[$i]->icon. "'></i>&nbsp;";
                $field[$i]->view == "" ? $view = "" : $view = "data-view='". $field[$i]->view. "'";
                $color = isset($field[$i]->color) ? $field[$i]->color : 'success';

                if($field[$i]->onclick != '') { $click = 'onclick="'.$field[$i]->onclick.'"'; } else { $click = ''; }
                $html .= '&nbsp;<a href="'. $field[$i]->href .'" id="'. $field[$i]->id .'" '.$click.' '.$view.'  class="btn btn-' . $color . ' ' . $field[$i]->class . '" >' . $icon . $field[$i]->label . '</a>';
            }

        	$i++;
        }

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
        $html = "";

        foreach(html::getForm($form) as $field) {
            //text inputs...
            if($field['name'] == $name) {
				$field[0]->readonly == 'true' ? $readonly = "readonly='true'" : $readonly = "";
                $field[0]->disabled == 'true' ? $disabled = "disabled='disabled'" : $disabled = "";
                $field[0]->onchange != "" ? $onchange = 'onchange="'.$field[0]->onchange.'"' : $onchange = "";
                $field[0]->onkeyup != "" ? $onkeyup = " onkeyup='".$field[0]->onkeyup."'" : $onkeyup = "";
                if($field[0]->type != 'hidden') $html .= "<div id='".$field[0]->name."-field' class='mb-3'>";
                if($field[0]->type != 'hidden' && $field[0]->label != "") $html .= "<label for='".$field[0]->id."' class='form-label'>".language::get($field[0]->label)."</label>";
                if($field[0]->type != 'hidden' && $field[0]->label != "") $html .= "<div class='controls'>";
                $html .= "<input type='".$field[0]->type."' id='".$field[0]->id."' value='".str_replace("'","&#39;",$default)."' name='".$field[0]->name."'";
                if($field[0]->type != 'hidden') $html .= $disabled." ".$onchange." ".$onkeyup." ".$readonly." placeholder='".language::get($field[0]->placeholder)."' class='form-control ".$field[0]->clase."' autocomplete='off'";
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
        $html = "";

        foreach(html::getForm($form) as $field) {
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
        $html = "";

        foreach(html::getForm($form) as $field) {
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
        $html = "";

        foreach(html::getForm($form) as $field) {
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
        $html = "";

        foreach(html::getForm($form) as $field) {
            //text inputs...
            if($field['name'] == $name) {
				$field[0]->readonly == 'true' ? $readonly = "readonly='true'" : $readonly = "";
                $field[0]->disabled == 'true' ? $disabled = "disabled='disabled'" : $disabled = "";
                $html .= "<div id='".$field[0]->name."-field' class='mb-3'>";
                if($field[0]->label != "") $html .= "<label for='".$field[0]->id."' class='form-label'>".language::get($field[0]->label)."</label>";
                $html .= "<input type='date' id='".$field[0]->id."' value='".$default."' name='".$field[0]->name."'";
                $html .= $disabled." ".$readonly." class='form-control' autocomplete='off'>";
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
        $html = "";

        foreach(html::getForm($form) as $field) {
            //text inputs...
            if($field['name'] == $name) {
                $field[0]->disabled == 'true' ? $disabled = "disabled='disabled'" : $disabled = "";
                $field[0]->onchange != "" ? $onchange = "onchange='".$field[0]->onchange."'" : $onchange = "";
                $html .= "<div id='".$field[0]->name."-field' class='mb-3'>";
                if($field[0]->label != "") $html .= "<label for='".$field[0]->id."' class='form-label'>".language::get($field[0]->label)."</label>";
                if($field[0]->label != "") $html .= "<div class='controls'>";
                $html .= "<textarea id='".$field[0]->id."' maxlength='".$field[0]->maxlength."' placeholder='".language::get($field[0]->placeholder)."' name='".$field[0]->name."' rows='".$field[0]->rows."' cols='".$field[0]->cols."' class='form-control' ".$disabled." ".$onchange.">".$default."</textarea>";
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

        $html = "";

        foreach(html::getForm($form) as $field) {
            //text inputs...
            if($field['name'] == $name) {
                $field[0]->disabled == 'true' ? $disabled = "disabled='disabled'" : $disabled = "";
                $field[0]->onchange != "" ? $onchange = "onchange='".$field[0]->onchange."'" : $onchange = "";
                if($field[0]->label != "") $html .= "<label for='".$field[0]->id."' class='form-label'>".language::get($field[0]->label)."</label>";
                $html .= "<textarea id='editor' name='".$field[0]->name."' maxlength='".$field[0]->maxlength."' placeholder='".language::get($field[0]->placeholder)."' rows='".$field[0]->rows."' cols='".$field[0]->cols."' class='form-control editor' ".$disabled." ".$onchange.">".$default."</textarea>";
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
        $html = "";

        foreach(html::getForm($form) as $field) {
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
        $html = "";

        foreach(html::getForm($form) as $field) {
            if($field['name'] == $name) {
                $field[0]->disabled == 'true' ? $disabled = "disabled='disabled'" : $disabled = "";
                $field[0]->onchange != "" ? $onchange = "onchange='".$field[0]->onchange."'" : $onchange = "";
                $html .= "<div id='".$field[0]->name."-field' class='mb-3'>";
                if($field[0]->label != "") $html .= "<label class='form-label' for='".$field[0]->id."'>".language::get($field[0]->label)."</label>";
                $html .= "<select id='".$field[0]->id."' name='".$field[0]->name."' ".$onchange." class='".$class." form-control' ".$disabled.">";

                database::query('SELECT * FROM #_usergroups');
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
        $html = "";

        foreach(html::getForm($form) as $field) {
            if($field['name'] == $name) {
                $field[0]->disabled == 'true' ? $disabled = "disabled='disabled'" : $disabled = "";
                $field[0]->onchange != "" ? $onchange = "onchange='".$field[0]->onchange."'" : $onchange = "";
                $html .= "<div id='".$field[0]->name."-field' class='mb-3'>";
                if($field[0]->label != "") $html .= "<label class='form-label' for='".$field[0]->id."'>".language::get($field[0]->label)."</label>";
                $html .= "<select id='".$field[0]->id."' name='".$field[0]->name."' ".$onchange." class='".$class." form-select' ".$disabled.">";

                database::query('SELECT id, username FROM #_users');
                $rows = database::fetchObjectList();

                $html .= "<option value=''>".language::get('FOXY_SELECT_USER')."</option>";

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
     * Method to render a select box
     * @param $form string the form name
     * @param $name string the field name
     * @param $default mixed optional default value
     * @param $options array optional array of options
     * @return $html string a complete select field html
    */
    public static function getListField($form, $name, $default='', $options=null, $key='', $value='', $combobox=false)
    {
        $html = "";

        foreach(html::getForm($form) as $field) {
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
				$combobox == true ? $class = 'combobox ' : $class = '';
                $html .= "<div id='".$field[0]->name."-field' class='".$input[0]."'>";
                if($field[0]->label != "") $html .= "<label class='form-label' for='".$field[0]->id."'>".language::get($field[0]->label)."</label>";
                $html .= "<select id='".$field[0]->id."' name='".$field[0]->name . $multiple[1] ."' ". $onchange . $multiple[0] ." class='custom-select". $class ." ". $input[1] ." ".$field[0]->classe. " ".  $multiple[2] ." form-select' ".$disabled. $multiple[3] .">";

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
        $html = "";

        foreach(html::getForm($form) as $field) {
            if($field['name'] == $name) {
                $field[0]->onclick != "" ? $onclick = "onclick='".$field[0]->onclick."'" : $onclick = "";
                $html .= "<div class='mb-3'>";
                foreach($field[0]->option as $option) {
                    $html .= "<div id='".$field[0]->name."-field' class='form-check-inline'>";
                    $default == $option['value'] ? $checked = "checked='checked';" : $checked = "";
                    $html .= "<input type='checkbox' class='form-check-input' name='".$field[0]->name."' id='".$field[0]->id."' value='".$option['value']."' ".$onclick."  data-message='".language::get($field[0]->message)."'>";
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
        $html = "";

        foreach(html::getForm($form) as $field) {
            if($field['name'] == $name) {
                $field[0]->onclick != "" ? $onclick = "onclick='".$field[0]->onclick."'" : $onclick = "";
                $html .= "<div class='mb-3'>";
                foreach($field[0]->option as $option) {
                    $html .= "<div id='".$field[0]->name."-field' class='form-check-inline'>";
                    $default == $option['value'] ? $checked = "checked='checked';" : $checked = "";
                    $html .= "<input type='radio' class='form-check-input' name='".$field[0]->name."' id='".$field[0]->id."' value='".$option['value']."' ".$onclick."  data-message='".language::get($field[0]->message)."'>";
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
        $html = "";

		$dir = opendir($folder);
		while (false !== ($file = readdir($dir))) {
			if( $file != "." && $file != "..") {
				$ficheros[] = $file;
			}
		}
		closedir($dir);

        foreach(html::getForm($form) as $field) {
            if($field['name'] == $name) {
                $field[0]->disabled == 'true' ? $disabled = "disabled='disabled'" : $disabled = "";
                $field[0]->onchange != "" ? $onchange = "onchange='".$field[0]->onchange."'" : $onchange = "";
                $html .= "<div id='".$field[0]->name."-field' class='mb-3'>";
                if($field[0]->label != "") $html .= "<label class='form-label' for='".$field[0]->id."'>".language::get($field[0]->label)."</label>";
                $html .= "<select class='form-control' id='".$field[0]->id."' name='".$field[0]->name."' ".$onchange." ".$disabled.">";
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
        $html = "";

		$sub_directories = array_map('basename', glob($directory_path . '/*', GLOB_ONLYDIR));

        foreach(html::getForm($form) as $field) {
            if($field['name'] == $name) {
                $field[0]->disabled == 'true' ? $disabled = "disabled='disabled'" : $disabled = "";
                $field[0]->onchange != "" ? $onchange = "onchange='".$field[0]->onchange."'" : $onchange = "";
                $html .= "<div id='".$field[0]->name."-field' class='mb-3'>";
                if($field[0]->label != "") $html .= "<label class='form-label' for='".$field[0]->id."'>".language::get($field[0]->label)."</label>";
                $html .= "<select class='form-control' id='".$field[0]->id."' name='".$field[0]->name."' ".$onchange." ".$disabled.">";
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
}
