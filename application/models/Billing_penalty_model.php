<?php

class Billing_penalty_model extends CORE_Model{

    protected  $table="billing_penalties"; //table name
    protected  $pk_id="billing_penalty_id"; //primary key id


    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    function get_penalties_list(){
    	$sql="SELECT 
    		penalties.*,
    		m.month_name,
    		current_penalties.total_penalty_amount

    		FROM billing_penalties penalties
    		LEFT JOIN months m ON m.month_id = penalties.month_id
    		LEFT JOIN 

    		(
    		SELECT main.*, SUM(main.penalty_fee) as total_penalty_amount FROM 

		    	(SELECT 
				    penalties.*
				FROM
				    (SELECT 
				        b.connection_id,
				            b.control_no AS ref_no,
				            m.month_id,
				            mrp.meter_reading_year as year,
				            CONCAT('Penalty (', '', m.month_name, ' ', mrp.meter_reading_year, ')') AS transaction,
				            DATE_FORMAT(DATE_ADD(b.due_date, INTERVAL 1 DAY), '%m/%d/%Y') AS date_txn,
				            (CASE
				                WHEN
				                    payment.date_paid IS NULL
				                THEN
				                    (CASE
				                        WHEN DATE(NOW()) > b.due_date THEN b.penalty_amount
				                        ELSE 0
				                    END)
				                ELSE (CASE
				                    WHEN DATE(payment.date_paid) > b.due_date THEN b.penalty_amount
				                    ELSE (CASE
				                        WHEN
				                            DATE(NOW()) > DATE(b.due_date)
				                        THEN
				                            (CASE
				                                WHEN payment.payment_amount >= (b.amount_due + b.charges_amount) THEN 0
				                                ELSE b.penalty_amount
				                            END)
				                        ELSE 0
				                    END)
				                END)
				            END) AS penalty_fee
				    FROM
				        billing b
				    LEFT JOIN meter_reading_period mrp ON mrp.meter_reading_period_id = b.meter_reading_period_id
				    LEFT JOIN (SELECT 
				        bpi.billing_id,
				            MAX(date_paid) AS date_paid,
				            (SUM(payment_amount) + SUM(deposit_payment)) AS payment_amount
				    FROM
				        billing_payment_items bpi
				    LEFT JOIN billing_payments bp ON bp.billing_payment_id = bpi.billing_payment_id
				    LEFT JOIN billing b ON b.billing_id = bpi.billing_id
				    WHERE
				        bp.is_active = TRUE
				            AND bp.is_deleted = FALSE
				            AND bp.date_paid <= b.due_date
				    GROUP BY bpi.billing_id) AS payment ON payment.billing_id = b.billing_id
				    LEFT JOIN months m ON m.month_id = mrp.month_id
				    GROUP BY b.billing_id) AS penalties 
				UNION ALL SELECT 
				    sd_penalties.*
				FROM
				    (SELECT 
				        sd.connection_id,
				            sd.disconnection_code AS ref_no,
				            MONTH(sd.service_date) as month_id,
				            YEAR(sd.service_date) as year,
				            'Disconnection Penalty' AS transaction,
				            IF(sd.service_date > DATE(CONCAT(YEAR(sd.service_date), '-', MONTH(sd.service_date), '-20')), DATE_FORMAT(sd.service_date, '%m/%d/%Y'), DATE_FORMAT(DATE(CONCAT(YEAR(sd.service_date), '-', MONTH(sd.service_date), '-21')), '%m/%d/%Y')) AS date_txn,
				            (CASE
				                WHEN
				                    payment.date_paid IS NULL
				                THEN
				                    (CASE
				                        WHEN DATE(NOW()) > sd.due_date THEN sd.penalty_amount
				                        ELSE 0
				                    END)
				                ELSE (CASE
				                    WHEN DATE(payment.date_paid) > sd.due_date THEN sd.penalty_amount
				                    ELSE (CASE
				                        WHEN
				                            DATE(NOW()) > DATE(sd.due_date)
				                        THEN
				                            (CASE
				                                WHEN payment.payment_amount >= sd.meter_amount_due THEN 0
				                                ELSE sd.penalty_amount
				                            END)
				                        ELSE 0
				                    END)
				                END)
				            END) AS penalty_fee
				    FROM
				        service_disconnection sd
				    LEFT JOIN (SELECT 
				        bpi.disconnection_id,
				            MAX(date_paid) AS date_paid,
				            (SUM(payment_amount) + SUM(deposit_payment)) AS payment_amount
				    FROM
				        billing_payment_items bpi
				    LEFT JOIN billing_payments bp ON bp.billing_payment_id = bpi.billing_payment_id
				    LEFT JOIN service_disconnection sd ON sd.disconnection_id = bpi.disconnection_id
				    WHERE
				        bp.is_active = TRUE
				            AND bp.is_deleted = FALSE
				            AND bp.date_paid <= sd.due_date
				    GROUP BY bpi.disconnection_id) AS payment ON payment.disconnection_id = sd.disconnection_id
				    WHERE
				        sd.is_active = TRUE
				            AND sd.is_deleted = FALSE
				    GROUP BY sd.disconnection_id) AS sd_penalties
				) as main
				LEFT JOIN service_connection sc ON sc.connection_id = main.connection_id
				LEFT JOIN meter_inventory meter ON meter.meter_inventory_id = sc.meter_inventory_id
				WHERE main.penalty_fee > 0
		        
		        GROUP BY main.year, main.month_id
				ORDER BY sc.receipt_name ASC
				) as current_penalties ON current_penalties.month_id = penalties.month_id AND current_penalties.year = penalties.year

    		WHERE 

    		penalties.is_deleted = FALSE AND
    		penalties.is_active = TRUE AND
    		penalties.is_journal_posted = FALSE

    		ORDER BY penalties.year ASC, penalties.month_id ASC";
    	return $this->db->query($sql)->result();
    }

    function get_journal_entries($billing_penalty_id){
        $query = $this->db->query("SELECT main.* FROM 

        	(
			SELECT 
			    (SELECT 
			            billing_receivable_penalty_account_id
			        FROM
			            account_integration) AS account_id,
			    '' AS memo,
			    bp.billing_penalty_id,
			    bp.month_id,
			    bp.year,
			    (current_penalties.total_penalty_amount) AS dr_amount,
			    0 AS cr_amount
			FROM
			    billing_penalties bp
			        LEFT JOIN
			    (SELECT 
			        main.*, SUM(main.penalty_fee) AS total_penalty_amount
			    FROM
			        (SELECT 
			        penalties.*
			    FROM
			        (SELECT 
			        b.connection_id,
			            b.control_no AS ref_no,
			            m.month_id,
			            mrp.meter_reading_year AS year,
			            CONCAT('Penalty (', '', m.month_name, ' ', mrp.meter_reading_year, ')') AS transaction,
			            DATE_FORMAT(DATE_ADD(b.due_date, INTERVAL 1 DAY), '%m/%d/%Y') AS date_txn,
			            (CASE
			                WHEN
			                    payment.date_paid IS NULL
			                THEN
			                    (CASE
			                        WHEN DATE(NOW()) > b.due_date THEN b.penalty_amount
			                        ELSE 0
			                    END)
			                ELSE (CASE
			                    WHEN DATE(payment.date_paid) > b.due_date THEN b.penalty_amount
			                    ELSE (CASE
			                        WHEN
			                            DATE(NOW()) > DATE(b.due_date)
			                        THEN
			                            (CASE
			                                WHEN payment.payment_amount >= (b.amount_due + b.charges_amount) THEN 0
			                                ELSE b.penalty_amount
			                            END)
			                        ELSE 0
			                    END)
			                END)
			            END) AS penalty_fee
			    FROM
			        billing b
			    LEFT JOIN meter_reading_period mrp ON mrp.meter_reading_period_id = b.meter_reading_period_id
			    LEFT JOIN (SELECT 
			        bpi.billing_id,
			            MAX(date_paid) AS date_paid,
			            (SUM(payment_amount) + SUM(deposit_payment)) AS payment_amount
			    FROM
			        billing_payment_items bpi
			    LEFT JOIN billing_payments bp ON bp.billing_payment_id = bpi.billing_payment_id
			    LEFT JOIN billing b ON b.billing_id = bpi.billing_id
			    WHERE
			        bp.is_active = TRUE
			            AND bp.is_deleted = FALSE
			            AND bp.date_paid <= b.due_date
			    GROUP BY bpi.billing_id) AS payment ON payment.billing_id = b.billing_id
			    LEFT JOIN months m ON m.month_id = mrp.month_id
			    GROUP BY b.billing_id) AS penalties UNION ALL SELECT 
			        sd_penalties.*
			    FROM
			        (SELECT 
			        sd.connection_id,
			            sd.disconnection_code AS ref_no,
			            MONTH(sd.service_date) AS month_id,
			            YEAR(sd.service_date) AS year,
			            'Disconnection Penalty' AS transaction,
			            IF(sd.service_date > DATE(CONCAT(YEAR(sd.service_date), '-', MONTH(sd.service_date), '-20')), DATE_FORMAT(sd.service_date, '%m/%d/%Y'), DATE_FORMAT(DATE(CONCAT(YEAR(sd.service_date), '-', MONTH(sd.service_date), '-21')), '%m/%d/%Y')) AS date_txn,
			            (CASE
			                WHEN
			                    payment.date_paid IS NULL
			                THEN
			                    (CASE
			                        WHEN DATE(NOW()) > sd.due_date THEN sd.penalty_amount
			                        ELSE 0
			                    END)
			                ELSE (CASE
			                    WHEN DATE(payment.date_paid) > sd.due_date THEN sd.penalty_amount
			                    ELSE (CASE
			                        WHEN
			                            DATE(NOW()) > DATE(sd.due_date)
			                        THEN
			                            (CASE
			                                WHEN payment.payment_amount >= sd.meter_amount_due THEN 0
			                                ELSE sd.penalty_amount
			                            END)
			                        ELSE 0
			                    END)
			                END)
			            END) AS penalty_fee
			    FROM
			        service_disconnection sd
			    LEFT JOIN (SELECT 
			        bpi.disconnection_id,
			            MAX(date_paid) AS date_paid,
			            (SUM(payment_amount) + SUM(deposit_payment)) AS payment_amount
			    FROM
			        billing_payment_items bpi
			    LEFT JOIN billing_payments bp ON bp.billing_payment_id = bpi.billing_payment_id
			    LEFT JOIN service_disconnection sd ON sd.disconnection_id = bpi.disconnection_id
			    WHERE
			        bp.is_active = TRUE
			            AND bp.is_deleted = FALSE
			            AND bp.date_paid <= sd.due_date
			    GROUP BY bpi.disconnection_id) AS payment ON payment.disconnection_id = sd.disconnection_id
			    WHERE
			        sd.is_active = TRUE
			            AND sd.is_deleted = FALSE
			    GROUP BY sd.disconnection_id) AS sd_penalties) AS main
			    LEFT JOIN service_connection sc ON sc.connection_id = main.connection_id
			    LEFT JOIN meter_inventory meter ON meter.meter_inventory_id = sc.meter_inventory_id
			    WHERE
			        main.penalty_fee > 0
			    GROUP BY main.year , main.month_id
			    ORDER BY sc.receipt_name ASC) AS current_penalties ON current_penalties.month_id = bp.month_id
			        AND current_penalties.year = bp.year
			WHERE
			    billing_penalty_id = $billing_penalty_id


			UNION ALL 

			SELECT 
			    (SELECT 
			            billing_penalty_account_id
			        FROM
			            account_integration) AS account_id,
			    '' AS memo,
			    bp.billing_penalty_id,
			    bp.month_id,
			    bp.year,
			    0 AS dr_amount,
			    current_penalties.total_penalty_amount AS cr_amount
			FROM
			    billing_penalties bp
			        LEFT JOIN
			    (SELECT 
			        main.*, SUM(main.penalty_fee) AS total_penalty_amount
			    FROM
			        (SELECT 
			        penalties.*
			    FROM
			        (SELECT 
			        b.connection_id,
			            b.control_no AS ref_no,
			            m.month_id,
			            mrp.meter_reading_year AS year,
			            CONCAT('Penalty (', '', m.month_name, ' ', mrp.meter_reading_year, ')') AS transaction,
			            DATE_FORMAT(DATE_ADD(b.due_date, INTERVAL 1 DAY), '%m/%d/%Y') AS date_txn,
			            (CASE
			                WHEN
			                    payment.date_paid IS NULL
			                THEN
			                    (CASE
			                        WHEN DATE(NOW()) > b.due_date THEN b.penalty_amount
			                        ELSE 0
			                    END)
			                ELSE (CASE
			                    WHEN DATE(payment.date_paid) > b.due_date THEN b.penalty_amount
			                    ELSE (CASE
			                        WHEN
			                            DATE(NOW()) > DATE(b.due_date)
			                        THEN
			                            (CASE
			                                WHEN payment.payment_amount >= (b.amount_due + b.charges_amount) THEN 0
			                                ELSE b.penalty_amount
			                            END)
			                        ELSE 0
			                    END)
			                END)
			            END) AS penalty_fee
			    FROM
			        billing b
			    LEFT JOIN meter_reading_period mrp ON mrp.meter_reading_period_id = b.meter_reading_period_id
			    LEFT JOIN (SELECT 
			        bpi.billing_id,
			            MAX(date_paid) AS date_paid,
			            (SUM(payment_amount) + SUM(deposit_payment)) AS payment_amount
			    FROM
			        billing_payment_items bpi
			    LEFT JOIN billing_payments bp ON bp.billing_payment_id = bpi.billing_payment_id
			    LEFT JOIN billing b ON b.billing_id = bpi.billing_id
			    WHERE
			        bp.is_active = TRUE
			            AND bp.is_deleted = FALSE
			            AND bp.date_paid <= b.due_date
			    GROUP BY bpi.billing_id) AS payment ON payment.billing_id = b.billing_id
			    LEFT JOIN months m ON m.month_id = mrp.month_id
			    GROUP BY b.billing_id) AS penalties UNION ALL SELECT 
			        sd_penalties.*
			    FROM
			        (SELECT 
			        sd.connection_id,
			            sd.disconnection_code AS ref_no,
			            MONTH(sd.service_date) AS month_id,
			            YEAR(sd.service_date) AS year,
			            'Disconnection Penalty' AS transaction,
			            IF(sd.service_date > DATE(CONCAT(YEAR(sd.service_date), '-', MONTH(sd.service_date), '-20')), DATE_FORMAT(sd.service_date, '%m/%d/%Y'), DATE_FORMAT(DATE(CONCAT(YEAR(sd.service_date), '-', MONTH(sd.service_date), '-21')), '%m/%d/%Y')) AS date_txn,
			            (CASE
			                WHEN
			                    payment.date_paid IS NULL
			                THEN
			                    (CASE
			                        WHEN DATE(NOW()) > sd.due_date THEN sd.penalty_amount
			                        ELSE 0
			                    END)
			                ELSE (CASE
			                    WHEN DATE(payment.date_paid) > sd.due_date THEN sd.penalty_amount
			                    ELSE (CASE
			                        WHEN
			                            DATE(NOW()) > DATE(sd.due_date)
			                        THEN
			                            (CASE
			                                WHEN payment.payment_amount >= sd.meter_amount_due THEN 0
			                                ELSE sd.penalty_amount
			                            END)
			                        ELSE 0
			                    END)
			                END)
			            END) AS penalty_fee
			    FROM
			        service_disconnection sd
			    LEFT JOIN (SELECT 
			        bpi.disconnection_id,
			            MAX(date_paid) AS date_paid,
			            (SUM(payment_amount) + SUM(deposit_payment)) AS payment_amount
			    FROM
			        billing_payment_items bpi
			    LEFT JOIN billing_payments bp ON bp.billing_payment_id = bpi.billing_payment_id
			    LEFT JOIN service_disconnection sd ON sd.disconnection_id = bpi.disconnection_id
			    WHERE
			        bp.is_active = TRUE
			            AND bp.is_deleted = FALSE
			            AND bp.date_paid <= sd.due_date
			    GROUP BY bpi.disconnection_id) AS payment ON payment.disconnection_id = sd.disconnection_id
			    WHERE
			        sd.is_active = TRUE
			            AND sd.is_deleted = FALSE
			    GROUP BY sd.disconnection_id) AS sd_penalties) AS main
			    LEFT JOIN service_connection sc ON sc.connection_id = main.connection_id
			    LEFT JOIN meter_inventory meter ON meter.meter_inventory_id = sc.meter_inventory_id
			    WHERE
			        main.penalty_fee > 0
			    GROUP BY main.year , main.month_id
			    ORDER BY sc.receipt_name ASC) AS current_penalties ON current_penalties.month_id = bp.month_id
			        AND current_penalties.year = bp.year
			WHERE
			    billing_penalty_id = $billing_penalty_id

			) as main WHERE main.dr_amount > 0 or main.cr_amount > 0
			");
					return $query->result();
    } 


 }