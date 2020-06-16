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
     * @param $form string the form xml name
    */
    public function getForm($form)
    {
        $output = simplexml_load_file('component/forms/'.$form.'.xml');
        return $output;
    }

    /**
     * Method to render a complete table
    */
    public function renderTable($id, $key, $linked, $data, $fields=array(), $columns=array())
    {
        $view  = factory::get('application')->getVar('view');
        $url   = factory::get('url');

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
                $field == $linked ? $text = '<a href="'.$url->genUrl('index.php?view='.$view.'&layout=edit&id='.$d->{$key}).'">'.$d->{$field}.'</a>' : $text = $d->{$field};
                $html .= '<td>'.$text.'</td>';
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
     * Method to load a form
     * @param $form string the form xml name
    */
    public function renderFilters($form, $view)
    {
    	$app    = factory::get('application');
        $lang   = factory::get('language');
        $db     = factory::get('database');
        $user   = factory::get('user');

        $fields = simplexml_load_file('component/forms/filters_'.$form.'.xml');
        $html   = '<div class="form-inline my-3">';
        $html  .= '<input type="hidden" name="view" value="'.$view.'">';

        $i = 0;
        foreach($fields as $field) {

            if($field->getName() == "field"){
                if($i > 0) { $html .= '&nbsp;'; }

                if($field[$i]->type == 'text') {

                    $field_name = (string)$field[$i]->name;
                    $html .= $this->getTextField($form, $field_name, $_GET[''.$field_name.'']);
                }
                if($field[$i]->type == 'date' || $field[$i]->type == 'calendar') {

                    $field_name = (string)$field[$i]->name;
                    $html .= $this->getDateField($form, $field_name, $_GET[''.$field_name.'']);
                    $html .= "<script>document.addEventListener('DOMContentLoaded', function(event) { $(function(){ $('#".(string)$field[$i]->id."-icon').datetimepicker({sideBySide: false,format: '".(string)$field[$i]->format."'}); }); });</script>";
                }
                if($field[$i]->type == 'users') {

                    $field_name = (string)$field[$i]->name;
                    $html .= $this->getUsersField($form, $field_name, $_GET[''.$field_name.'']);
                }
                if($field[$i]->type == 'list') {

                    $field_name = (string)$field[$i]->name;
                    $html .= $this->getListField($form, $field_name, $_GET[''.$field_name.'']);
                }

            }
        	$i++;
        }

        $html .= '&nbsp;<button class="btn btn-success" type="submit">'.$lang->get('FOXY_SEARCH').'</button>';
        $html .= '</div>';

        return $html;
    }

    public function renderButtons($form, $view)
    {
    	$app    = factory::get('application');
        $lang   = factory::get('language');

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
     * @param $form string the form name
     * @param $name string the field name
     * @param $default mixed optional default value
     * @return $html string a complete input field html
    */
    public function getTextField($form, $name, $default='')
    {
        $app    = factory::get('application');
        $lang   = factory::get('language');

        $html = "";

        foreach($this->getForm($form) as $field) {
            //text inputs...
            if($field['name'] == $name) {
				$field[0]->readonly == 'true' ? $readonly = "readonly='true'" : $readonly = "";
                $field[0]->disabled == 'true' ? $disabled = "disabled='disabled'" : $disabled = "";
                $field[0]->onchange != "" ? $onchange = 'onchange="'.$field[0]->onchange.'"' : $onchange = "";
                $field[0]->onkeyup != "" ? $onkeyup = " onkeyup='".$field[0]->onkeyup."'" : $onkeyup = "";
                if($field[0]->type != 'hidden') $html .= "<div id='".$field[0]->name."-field' class='form-group'>";
                if($field[0]->type != 'hidden' && $field[0]->label != "") $html .= "<label for='".$field[0]->id."'><a class='hasTip' title='".$lang->get($field[0]->placeholder)."'>".$lang->get($field[0]->label)."</a></label>";
                if($field[0]->type != 'hidden' && $field[0]->label != "") $html .= "<div class='controls'>";
                $html .= "<input type='".$field[0]->type."' id='".$field[0]->id."' value='".str_replace("'","&#39;",$default)."' name='".$field[0]->name."'";
                if($field[0]->type != 'hidden') $html .= $disabled." data-message='".$lang->get($field[0]->message)."' ".$onchange." ".$onkeyup." ".$readonly." placeholder='".$lang->get($field[0]->placeholder)."' class='form-control ".$field[0]->clase."' autocomplete='off'";
                $html .= ">";
                //if($field[0]->type != 'hidden') $html .= "<span id='".$field[0]->name."-msg'></span>";
                if($field[0]->type != 'hidden' && $field[0]->label != "") $html .= "</div>";
                if($field[0]->type != 'hidden') $html .= "</div>";
            }
        }
        return $html;
    }

    /**
     * Method to render an email field
     * @param $form string the form name
     * @param $name string the field name
     * @param $default mixed optional default value
     * @return $html string a complete input field html
    */
    public function getEmailField($form, $name, $default='')
    {
        $app    = factory::get('application');
        $lang   = factory::get('language');

        $html = "";

        foreach($this->getForm($form) as $field) {
            //text inputs...
            if($field['name'] == $name) {
                $field[0]->disabled == 'true' ? $disabled = "disabled='disabled'" : $disabled = "";
                $field[0]->required == 'true' ? $required = "required='true'" : $required = "";
                $field[0]->onchange != "" ? $onchange = "onchange='".$field[0]->onchange."'" : $onchange = "";
                $field[0]->onkeyup != "" ? $onkeyup = " onkeyup='".$field[0]->onkeyup."'" : $onkeyup = "";
                $html .= "<div id='".$field[0]->name."-field' class='form-group'>";
                if($field[0]->label != "") $html .= "<label for='".$field[0]->id."'><a class='hasTip' title='".$lang->get($field[0]->placeholder)."'>".$lang->get($field[0]->label)."</a></label>";
                if($field[0]->label != "") $html .= "<div class='controls'>";
                $html .= "<input type='".$field[0]->type."' name='".$field[0]->name."' style='display:none;' />";
                $html .= "<input type='".$field[0]->type."' id='".$field[0]->id."' value='".$default."' name='".$field[0]->name."'";
                $html .= $disabled.' '.$required.' data-error="'.$lang->get($field[0]->message).'" '.$onchange.$onkeyup.' placeholder="'.$lang->get($field[0]->placeholder).'" class="form-control '.$field[0]->clase.'"  pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" autocomplete="off"';
                if($field[0]->remote != '') { $html .= " data-remote='".$field[0]->remote."'"; }
                $html .= ">";
                $html .= "<div class='help-block with-errors'></div>";
                if($field[0]->label != "") $html .= "</div>";
                $html .= "</div>";
            }
        }
        return $html;
    }

    /**
     * Method to render a password field
     * @param $form string the form name
     * @param $name string the field name
     * @param $default mixed optional default value
     * @return $html string a complete input field html
    */
    public function getPasswordField($form, $name, $default='')
    {
        $app    = factory::get('application');
        $lang   = factory::get('language');

        $html = "";

        foreach($this->getForm($form) as $field) {
            //text inputs...
            if($field['name'] == $name) {
                $field[0]->disabled == 'true' ? $disabled = "disabled='disabled'" : $disabled = "";
                $field[0]->required == 'true' ? $required = "required='true'" : $required = "";
                $field[0]->onchange != "" ? $onchange = "onchange='".$field[0]->onchange."'" : $onchange = "";
                $field[0]->onkeyup != "" ? $onkeyup = " onkeyup='".$field[0]->onkeyup."'" : $onkeyup = "";
                $html .= "<div id='".$field[0]->name."-field' class='form-group'>";
                if($field[0]->label != "") $html .= "<label for='".$field[0]->id."'><a class='hasTip' title='".$lang->get($field[0]->placeholder)."'>".$lang->get($field[0]->label)."</a></label>";
                if($field[0]->label != "") $html .= "<div class='controls'>";
                $html .= "<input type='".$field[0]->type."' name='".$field[0]->name."' style='display:none;' />";
                $html .= "<input type='".$field[0]->type."' data-minlength='".$field[0]->minlength."' id='".$field[0]->id."' value='".$default."' name='".$field[0]->name."'";
                if($field[0]->match != '') { $html .= "data-match='".$field[0]->match."' "; }
                $html .= $disabled.' '.$required.' data-error="'.$lang->get($field[0]->message).'" '.$onchange.$onkeyup.' placeholder="'.$lang->get($field[0]->placeholder).'" class="form-control '.$field[0]->clase.'" autocomplete="off"';
                $html .= ">";
                $html .= "<div class='help-block with-errors'></div>";
                if($field[0]->label != "") $html .= "</div>";
                $html .= "</div>";
            }
        }
        return $html;
    }

    /**
     * Method to render a input box
     * @param $form string the form name
     * @param $name string the field name
     * @param $default mixed optional default value
     * @return $html string a complete input field html
    */
    public function getDateField($form, $name, $default='')
    {
        $app    = factory::get('application');
        $lang   = factory::get('language');

        $html = "";

        foreach($this->getForm($form) as $field) {
            //text inputs...
            if($field['name'] == $name) {
				$field[0]->readonly == 'true' ? $readonly = "readonly='true'" : $readonly = "";
                $field[0]->disabled == 'true' ? $disabled = "disabled='disabled'" : $disabled = "";
                $html .= "<div id='".$field[0]->name."-field' class='form-group'>";
                if($field[0]->label != "") $html .= "<label for='".$field[0]->id."'><a class='hasTip' title='".$lang->get($field[0]->placeholder)."'>".$lang->get($field[0]->label)."</a></label>";
                $html .= "<div class='input-group date' id='".$field[0]->id."-icon'>";
                $html .= "<input type='text' id='".$field[0]->id."' value='".$default."' name='".$field[0]->name."'";
                $html .= $disabled." data-message='".$lang->get($field[0]->message)."' ".$readonly." class='form-control input-datepicker-autoclose' autocomplete='off'>";
                $html .= "<span class='input-group-addon'><span class='glyphicon glyphicon-calendar'></span></span>";
                $html .= "</div>";
                $html .= "</div>";
            }
        }
        return $html;
    }

    /**
     * Method to render a input box
     * @param $form string the form name
     * @param $name string the field name
     * @param $default mixed optional default value
     * @param $editor bool optional convert the textarea in a editor
     * @return $html string a complete input field html
    */
    public function getTextareaField($form, $name, $default='')
    {
        $app    = factory::get('application');
        $lang   = factory::get('language');

        $html = "";

        foreach($this->getForm($form) as $field) {
            //text inputs...
            if($field['name'] == $name) {
                $field[0]->disabled == 'true' ? $disabled = "disabled='disabled'" : $disabled = "";
                $field[0]->onchange != "" ? $onchange = "onchange='".$field[0]->onchange."'" : $onchange = "";
                $html .= "<div id='".$field[0]->name."-field' class='form-group'>";
                if($field[0]->label != "") $html .= "<label for='".$field[0]->id."'><a class='hasTip' title='".$lang->get($field[0]->placeholder)."'>".$lang->get($field[0]->label)."</a></label>";
                if($field[0]->label != "") $html .= "<div class='controls'>";
                $html .= "<textarea id='".$field[0]->id."' maxlength='".$field[0]->maxlength."' placeholder='".$lang->get($field[0]->placeholder)."' name='".$field[0]->name."' rows='".$field[0]->rows."' cols='".$field[0]->cols."' class='form-control' ".$disabled." ".$onchange.">".$default."</textarea>";
                //$html .= "<span id='".$field[0]->name."-msg'></span>";
                if($field[0]->label != "") $html .= "</div>";
                $html .= "</div>";
            }
        }

        return $html;
    }

    /**
     * Method to render a input box
     * @param $form string the form name
     * @param $name string the field name
     * @param $default mixed optional default value
     * @param $editor bool optional convert the textarea in a editor
     * @return $html string a complete input field html
    */
    public function getEditorField($form, $name, $default='')
    {
        $app    = factory::get('application');
        $lang   = factory::get('language');

        $html = "";

        foreach($this->getForm($form) as $field) {
            //text inputs...
            if($field['name'] == $name) {
                $field[0]->disabled == 'true' ? $disabled = "disabled='disabled'" : $disabled = "";
                $field[0]->onchange != "" ? $onchange = "onchange='".$field[0]->onchange."'" : $onchange = "";
                if($field[0]->label != "") $html .= "<label for='".$field[0]->id."'><a class='hasTip' title='".$lang->get($field[0]->placeholder)."'>".$lang->get($field[0]->label)."</a></label>";
                $html .= "<textarea id='".$field[0]->id."' maxlength='".$field[0]->maxlength."' placeholder='".$lang->get($field[0]->placeholder)."' name='".$field[0]->name."' rows='".$field[0]->rows."' cols='".$field[0]->cols."' class='form-control editor' ".$disabled." ".$onchange.">".$default."</textarea>";
            }
        }

        return $html;
    }


    /**
     * Method to render a form button
     * @param $form string the form name
     * @param $name string the field name
     * @return $html string a complete html button
    */
    public function getButton($form, $name)
    {
        $lang   = factory::get('language');

        $html = "";

        foreach($this->getForm($form) as $field) {
            if($field['name'] == $name) {
                $field[0]->disabled == 'true' ? $disabled = "disabled='disabled'" : $disabled = "";
                $field[0]->onclick != "" ? $onclick = "onclick='".$field[0]->onclick."'" : $onclick = "";
                $field[0]->type == 'submit' ? $type = "type='".$field[0]->type."'" : $type = "";
                $html .= "<button $type id='".$field[0]->id."' ".$disabled." ".$onclick." class='btn btn-".$field[0]->color." ".$field[0]->clase."'>".$lang->get($field[0]->value)."</button>";
            }
        }
        return $html;
    }

    /**
     * Method to render a repeatable field require jquery ui
     * @param $form string the form name
     * @param $fields array of field names
	 * @param $tmpl array of default values
	 * @param $list object to fill the list field
	 * @param $value string the value field for list fields
	 * @param $key string the key field for list fields
	 * @param $target string the modal url formmodal fields
	 * @param $placeholder string a placeholder for modal fields
	 * @param $uniqid string a uniqid for modal fields
	 * @see https://github.com/Rhyzz/repeatable-fields
     * @return $html string a complete repeatable field
    */
    public function getRepeatable($form, $fields, $tmpl=null, $list, $value, $key, $target, $placeholder, $uniqid)
    {
        $lang   = factory::get('language');

        $html = "<div class='repeatable'>";
		$html .= "<table class='wrapper' width='100%'>";
		$html .= "<thead><tr><td width='10%' valign='bottom' colspan='4'><span class='add btn btn-success'><i class='fa fa-plus'></i></span></td></tr></thead>";
		$html .= "<tbody class='container'>";

		$html .= "<tr class='template row'>";
		$html .= "<td width='10%'><div class='form-group'></div></td>";

		foreach($fields as $field) {
			foreach($this->getForm($form) as $row) {
				if($row['name'] == $field) {
				$row[0]->width == '' ? $width = '40%' : $width = $row[0]->width;
				if($row[0]->type == 'text') { $html .= "<td width='".$width."'>".$this->getTextField($form, $field)."</td>"; }
				if($row[0]->type == 'textarea') { $html .= "<td width='".$width."'>".$this->getTextareaField($form, $field)."</td>"; }
				if($row[0]->type == 'list') { $html .= "<td width='".$width."'>".$this->getListField($form, $field, "", $list, $value, $key)."</td>"; }
				if($row[0]->type == 'checkbox') { $html .= "<td width='".$width."'>".$this->getCheckboxField($form, $field)."</td>"; }
				if($row[0]->type == 'radio') { $html .= "<td width='".$width."'>".$this->getRadioField($form, $field)."</td>"; }
				if($row[0]->type == 'modal') { $html .= "<td width='".$width."'>".$this->getModalField($form, $field, '', $target, $placeholder, $uniqid)."</td>"; }
				}
			}
		}


		$html .= '<td valign="bottom" width="10%" align="right"><div class="form-group"><span class="remove btn btn-danger"><i class="fa fa-minus"></i></span></div></td></tr>';

		if($tmpl != null) {
			foreach($tmpl as $item) {
				$html .= "<tr class='row fromdb'>";
				$html .= "<td width='10%'><div class='form-group'></div></td>";
				foreach($fields as $field) {
					foreach($this->getForm($form) as $row) {
						if($row['name'] == $field) {
						$row[0]->width == '' ? $width = '40%' : $width = $row[0]->width;
						if($row[0]->type == 'text') { $html .= "<td width='".$width."'>".$this->getTextField($form, $field, $item->$field)."</td>"; }
						if($row[0]->type == 'textarea') { $html .= "<td width='".$width."'>".$this->getTextareaField($form, $field, $item->$field)."</td>"; }
						if($row[0]->type == 'list') { $html .= "<td width='".$width."'>".$this->getListField($form, $field, $item->$field, $list, $value, $key)."</td>"; }
						if($row[0]->type == 'checkbox') { $html .= "<td width='".$width."'>".$this->getCheckboxField($form, $field, $item->$field)."</td>"; }
						if($row[0]->type == 'radio') { $html .= "<td width='".$width."'>".$this->getRadioField($form, $field, $item->$field)."</td>"; }
						if($row[0]->type == 'modal') { $html .= "<td width='".$width."'>".$this->getModalField($form, $field, '', $target, $placeholder, $uniqid)."</td>"; }
						}
					}
				}
				$html .= '<td width="10%" valign="bottom" align="right"><div class="form-group"><span class="remove btn btn-danger"><i class="fa fa-minus"></i></span></div></td></tr>';
			}

		} else {
			$html .= "<tr class='row'>";
			$html .= "<td width='10%'><div class='form-group'></div></td>";
			foreach($fields as $field) {
				foreach($this->getForm($form) as $row) {
					if($row['name'] == $field) {
					$row[0]->width == '' ? $width = '40%' : $width = $row[0]->width;
					if($row[0]->type == 'text') { $html .= "<td width='".$width."'>".$this->getTextField($form, $field)."</td>"; }
					if($row[0]->type == 'textarea') { $html .= "<td width='".$width."'>".$this->getTextareaField($form, $field)."</td>"; }
					if($row[0]->type == 'list') { $html .= "<td width='".$width."'>".$this->getListField($form, $field, "", $list, $value, $key)."</td>"; }
					if($row[0]->type == 'checkbox') { $html .= "<td width='".$width."'>".$this->getCheckboxField($form, $field)."</td>"; }
					if($row[0]->type == 'radio') { $html .= "<td width='".$width."'>".$this->getRadioField($form, $field)."</td>"; }
					if($row[0]->type == 'modal') { $html .= "<td width='".$width."'>".$this->getModalField($form, $field, '', $target, $placeholder, $uniqid)."</td>"; }
					}
				}
			}

			$html .= '<td width="10%" valign="bottom" align="right"><div class="form-group"><span class="remove btn btn-danger"><i class="fa fa-minus"></i></span></div></td>';
		}
		$html .= "<script>";
		$html .= '$(document).ready(function () {';
		$html .= '$(".repeatable").each(function() {';
		$html .= '$(this).repeatable_fields();';
		$html .= '});';
		$html .= '});';
		$html .= "</script>";
		$html .= '</tr></tbody></table>';
		$html .= '</div>';
        return $html;
    }

    /**
     * Method to render a usergroups select box
     * @param $form string the form name
     * @param $name string the field name
     * @param $default mixed optional default value
    */
    public function getUsergroupsField($form, $name, $default='')
    {
        $lang   = factory::get('language');
        $db     = factory::get('database');

        $html = "";

        foreach($this->getForm($form) as $field) {
            if($field['name'] == $name) {
                $field[0]->disabled == 'true' ? $disabled = "disabled='disabled'" : $disabled = "";
                $field[0]->onchange != "" ? $onchange = "onchange='".$field[0]->onchange."'" : $onchange = "";
                $html .= "<div id='".$field[0]->name."-field' class='form-group'>";
                if($field[0]->label != "") $html .= "<label class='control-label' for='".$field[0]->id."'><a class='hasTip' title='".$lang->get($field[0]->placeholder)."'>".$lang->get($field[0]->label)."</a></label>";
                $html .= "<select id='".$field[0]->id."' name='".$field[0]->name."' data-message='".$lang->get($field[0]->message)."' ".$onchange." class='".$class." form-control' ".$disabled.">";

                $db->query('SELECT * FROM #_usergroups');
                $rows = $db->fetchObjectList();

                $html .= "<option value=''>".$lang->get('FOXY_SELECT_USERGROUP')."</option>";

				foreach($rows as $row) {
					  $default == $row->id ? $selected = "selected='selected'" : $selected = "";
					  $html .= "<option value='".$row->id."' $selected>".$row->usergroup."</option>";
				}

                $html .= "</select>";
                //$html .= "<span id='".$field[0]->name."-msg'></span>";
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
    public function getUsersField($form, $name, $default='')
    {
        $lang   = factory::get('language');
        $db     = factory::get('database');
        $user   = factory::get('user');

        $html = "";

        foreach($this->getForm($form) as $field) {
            if($field['name'] == $name) {
                $field[0]->disabled == 'true' ? $disabled = "disabled='disabled'" : $disabled = "";
                $field[0]->onchange != "" ? $onchange = "onchange='".$field[0]->onchange."'" : $onchange = "";
                $html .= "<div id='".$field[0]->name."-field' class='form-group'>";
                if($field[0]->label != "") $html .= "<label class='control-label' for='".$field[0]->id."'><a class='hasTip' title='".$lang->get($field[0]->placeholder)."'>".$lang->get($field[0]->label)."</a></label>";
                $html .= "<select id='".$field[0]->id."' name='".$field[0]->name."' data-message='".$lang->get($field[0]->message)."' ".$onchange." class='".$class." form-control' ".$disabled.">";

                $db->query('SELECT id, username FROM #_users');
                $rows = $db->fetchObjectList();

                $html .= "<option value=''>".$lang->get('FOXY_SELECT_USER')."</option>";

				foreach($rows as $row) {
					  $default == '' ? $default = $user->id : $default = $default;
					  $default == $row->id ? $selected = "selected='selected'" : $selected = "";
					  $html .= "<option value='".$row->id."' $selected>".$row->username."</option>";
				}

                $html .= "</select>";
                //$html .= "<span id='".$field[0]->name."-msg'></span>";
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
    public function getListField($form, $name, $default='', $options=null, $key='', $value='', $combobox=false)
    {
        $lang   = factory::get('language');

        $html = "";

        foreach($this->getForm($form) as $field) {
            if($field['name'] == $name) {
                $field[0]->disabled == 'true' ? $disabled = "disabled='disabled'" : $disabled = "";
                if(isset($field[0]->button)){
                    $input[0] = "input-group mb-3";
                    $input[1] = "custom-select";
                }else{
                    $input[0] = "form-group";
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
                if($field[0]->label != "") $html .= "<label class='control-label' for='".$field[0]->id."'><a class='hasTip' title='".$lang->get($field[0]->placeholder)."'>".$lang->get($field[0]->label)."</a></label>";
                $html .= "<select id='".$field[0]->id."' name='".$field[0]->name . $multiple[1] ."' ". $onchange . $multiple[0] ." class='custom-select". $class ." ". $input[1] ." ".$field[0]->classe. " ".  $multiple[2] ." form-control' ".$disabled. $multiple[3] .">";

				foreach($field[0]->option as $option) {
					  $default == $option['value'] ? $selected = "selected='selected'" : $selected = "";
					  $option['onclick'] != '' ? $click = "onclick='".$option['onclick']."'" : $click = "";
					  $html .= "<option value='".$option['value']."' $click $selected>".$lang->get($option[0])."</option>";
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
                    $db->query($field[$i]->query);
                    $options    = $db->fetchObjectList();
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

                $html .= "</div>";
            }
        }
        return $html;
    }

	/**
     * Method to render a modal field
     * @param $form string the form name
     * @param $name string the field name
     * @param $default mixed optional default value
     * @return $html string a complete checkbox field html
    */
    public function getModalField($form, $name, $default='', $target='', $placeholder='', $uniqid='')
    {
        $lang   = factory::get('language');
        $uniqid == '' ? $uniqid = uniqid() : $uniqid = $uniqid;
        $html = "";
        foreach($this->getForm($form) as $field) {
            if($field['name'] == $name) {
				$target == '' ? $target = $field[0]->target : $target = $target;
				$placeholder == '' ? $placeholder = $field[0]->placeholder : $placeholder = $placeholder;
                $field[0]->onclick != "" ? $onclick = "onclick='".$field[0]->onclick."'" : $onclick = "";
                $html .= "<div id='".$field[0]->name."-field' class='form-group'>";
                $html .= "<div class='input-group'>";
				$html .= "<input type='text' class='form-control SearchBar' name='".$field[0]->name."' value='".$default."' id='input-".$uniqid."' placeholder='".$placeholder."'>";
				$html .= "<span class='input-group-btn'>";
				$html .= "<button class='btn btn-defaul SearchButton' id='searchBtn-".$uniqid."' type='button'>";
				$html .= "<span class='glyphicon glyphicon-search SearchIcon' ></span>";
				$html .= "</button>";
				$html .= "</span>";
				$html .= "</div>";
				$html .= "<!-- modal  -->";
				$html .= "<div class='modal fade' id='myModal-".$uniqid."' tabindex='-1' role='dialog' aria-labelledby='myModalLabel-".$uniqid."' aria-hidden='true'>";
				$html .= "<div class='modal-dialog'>";
				$html .= "<div class='modal-content'>";
				$html .= "<div class='modal-header'>";
				$html .= "<button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>";
				$html .= "<h4 class='modal-title' id='myModalLabel-".$uniqid."'>".$placeholder."</h4>";
				$html .= "</div>";
				$html .= "<div class='modal-body' id='modal-body-".$uniqid."'>";
				$html .= "</div>";
				$html .= "<div class='modal-footer'>";
				$html .= "<button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>";
				$html .= "</div>";
				$html .= "</div>";
				$html .= "</div>";
				$html .= "</div>";
				$html .= "<script>";
				$html .= "$(document).ready(function () {";
				$html .= "$('#searchBtn-".$uniqid."').on('click', function() {";
				$html .= "$('#myModal-".$uniqid."').modal('show'); ";
				$html .= "$('#modal-body-".$uniqid."').load('".$target."&btn=".$uniqid."');";
				$html .= "});";
				$html .= "});";
				$html .= "</script>";
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
        $lang   = factory::get('language');

        $html = "";

        foreach($this->getForm($form) as $field) {
            if($field['name'] == $name) {
                $field[0]->onclick != "" ? $onclick = "onclick='".$field[0]->onclick."'" : $onclick = "";
                $html .= "<div class='form-group'>";
                $html .= "<div id='".$field[0]->name."-field' class='checkbox'>";
                $html .= "<label class='checkbox'>";
                foreach($field[0]->option as $option) {
                    $default == $option['value'] ? $checked = "checked='checked';" : $checked = "";
                    $html .= "<input type='checkbox' class='checkbox' name='".$field[0]->name."' id='".$field[0]->id."' value='".$option['value']."' ".$onclick."  data-message='".$lang->get($field[0]->message)."'> ".$lang->get($option[0]);
                }
                $html .= "</label>";
                //$html .= "<span id='".$field[0]->name."-msg'></span>";
                $html .= "</div>";
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
    public function getRadioField($form, $name, $default='')
    {
        $lang   = factory::get('language');

        $html = "";

        foreach($this->getForm($form) as $field) {
            if($field['name'] == $name) {
                $field[0]->onclick != "" ? $onclick = "onclick='".$field[0]->onclick."'" : $onclick = "";
                $html .= "<div id='".$field[0]->name."-field' class='form-group'>";
				if($field[0]->label != "") $html .= "<label class='btn-group-label'><a class='hasTip' title='".$lang->get($field[0]->placeholder)."'>".$lang->get($field[0]->label)."</a></label> ";

        	//$html .= "<div class='col-sm-9'>";
                $html .= " <div class='btn-group ".$name."' data-toggle='buttons'>";

                foreach($field[0]->option as $option) {
                    $default == $option['value'] ? $checked = "checked='checked'" : $checked = "";
					$default == $option['value'] ? $class = "active" : $class = "";
					$html .= "<label class='btn btn-default ".$class."'>";
					$html .= "<input type='radio' name='".$field[0]->name."' id='".$field[0]->id."' ".$checked." value='".$option['value']."' ".$onclick."  data-message='".$lang->get($field[0]->message)."'> ".$lang->get($option[0]);
					$html .= "</label>";
                }

                //$html .= "</div>";
				$html .= "</div>";
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
    public function getFilesField($form, $name, $folder, $default='')
    {
    	$lang   = factory::get('language');

        $html = "";

		$dir = opendir($folder);
		while (false !== ($file = readdir($dir))) {
			if( $file != "." && $file != "..") {
				$ficheros[] = $file;
			}
		}
		closedir($dir);

        foreach($this->getForm($form) as $field) {
            if($field['name'] == $name) {
                $field[0]->disabled == 'true' ? $disabled = "disabled='disabled'" : $disabled = "";
                $field[0]->onchange != "" ? $onchange = "onchange='".$field[0]->onchange."'" : $onchange = "";
                $html .= "<div id='".$field[0]->name."-field' class='form-group'>";
                if($field[0]->label != "") $html .= "<label class='control-label' for='".$field[0]->id."'><a class='hasTip' title='".$lang->get($field[0]->placeholder)."'>".$lang->get($field[0]->label)."</a></label>";
                $html .= "<select class='form-control' id='".$field[0]->id."' name='".$field[0]->name."' data-message='".$lang->get($field[0]->message)."' ".$onchange." ".$disabled.">";
                $html .= "<option value=''>".$lang->get('FOXY_SELECT_AN_OPTION')."</option>";
				foreach($ficheros as $fichero) {
					  $default == $fichero ? $selected = "selected='selected'" : $selected = "";
					  $html .= "<option value='".$fichero."' $selected>".$fichero."</option>";
				}

                $html .= "</select>";
                //$html .= "<span id='".$field[0]->name."-msg'></span>";
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
    public function getFoldersField($form, $name, $directory_path, $default='')
    {
    	$lang   = factory::get('language');

        $html = "";

		$sub_directories = array_map('basename', glob($directory_path . '/*', GLOB_ONLYDIR));

        foreach($this->getForm($form) as $field) {
            if($field['name'] == $name) {
                $field[0]->disabled == 'true' ? $disabled = "disabled='disabled'" : $disabled = "";
                $field[0]->onchange != "" ? $onchange = "onchange='".$field[0]->onchange."'" : $onchange = "";
                $html .= "<div id='".$field[0]->name."-field' class='form-group'>";
                if($field[0]->label != "") $html .= "<label class='control-label' for='".$field[0]->id."'><a class='hasTip' title='".$lang->get($field[0]->placeholder)."'>".$lang->get($field[0]->label)."</a></label>";
                $html .= "<select class='form-control' id='".$field[0]->id."' name='".$field[0]->name."' data-message='".$lang->get($field[0]->message)."' ".$onchange." ".$disabled.">";
                $html .= "<option value=''>".$lang->get('FOXY_SELECT_AN_OPTION')."</option>";
				foreach($sub_directories as $directory) {
					  $default == $directory ? $selected = "selected='selected'" : $selected = "";
					  $html .= "<option value='".$directory."' $selected>".$directory."</option>";
				}

                $html .= "</select>";
                //$html .= "<span id='".$field[0]->name."-msg'></span>";
                $html .= "</div>";
            }
        }
        return $html;
    }
}
