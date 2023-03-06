<?php

class Institutional_invoice_items_model extends CORE_Model
{
    protected $table = "institutional_invoice_items";
    protected $pk_id = "institutional_item_id";
    protected $fk_id = "institutional_invoice_id";

    function __construct()
    {
        parent::__construct();
    }

}


?>
