<?php

class Matrix_institutional_items_model extends CORE_Model {
    protected  $table="matrix_institutional_items";
    protected  $pk_id="matrix_institutional_item_id";
    protected  $fk_id = "matrix_institutional_id";

    function __construct() {
        parent::__construct();
    }

}
?>