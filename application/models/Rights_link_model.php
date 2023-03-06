<?php
class Rights_link_model extends CORE_Model{
    protected  $table="rights_links"; //table name
    protected  $pk_id="link_id"; //primary key id
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
    function create_default_link_list(){
        $sql="INSERT INTO `rights_links` (`link_id`, `parent_code`, `link_code`, `link_name`) VALUES
                                          (1,'1','1-1','General Journal'),
                                          (2,'1','1-2','Cash Disbursement'),
                                          (3,'1','1-3','Purchase Journal'),
                                          (4,'1','1-4','Sales Journal'),
                                          (5,'1','1-5','Cash Receipt'),
                                          (6,'2','2-1','Purchase Order'),
                                          (7,'2','2-2','Purchase Invoice'),
                                          (8,'2','2-3','Record Payment'),
                                          -- (9,'15','15-2','Item Issuance'),
                                          (10,'15','15-3','Item Adjustment'),
                                          (11,'3','3-1','Sales Order'),
                                          (12,'3','3-2','Sales Invoice'),
                                          (13,'3','3-3','Record Payment'),
                                          (14,'4','4-2','Category Management'),
                                          (15,'4','4-3','Department Management'),
                                          (16,'4','4-4','Unit Management'),
                                          (17,'5','5-1','Product Management'),
                                          (18,'5','5-2','Supplier Management'),
                                          (19,'5','5-3','Customer Management'),
                                          (20,'6','6-1','Setup Tax'),
                                          (21,'6','6-2','Setup Chart of Accounts'),
                                          (22,'6','6-3','Account Integration'),
                                          (23,'6','6-4','Setup User Group'),
                                          (24,'6','6-5','Create User Account'),
                                          (25,'6','6-6','Setup Company Info'),
                                          (26,'7','7-1','Purchase Order for Approval'),
                                          (27,'9','9-1','Balance Sheet Report'),
                                          (28,'9','9-2','Income Statement'),
                                          (29,'4','4-1','Account Classification'),
                                          (30,'8','8-1','Sales Report'),
                                          (31,'15','15-4','Inventory Report'),
                                          (32,'5','5-4','Salesperson Management'),
                                          (33,'2','2-6','Item Adjustment (Out)'),
                                          (34,'8','8-3','Export Sales Summary'),
                                          (35,'9','9-3','Export Trial Balance'),
                                          (36,'6','6-7','Setup Check Layout'),
                                          (37,'9','9-4','AR Schedule'),
                                          (38,'9','9-6','Customer Subsidiary'),
                                          (39,'9','9-8','Account Subsidiary'),
                                          (40,'9','9-7','Supplier Subsidiary'),
                                          (41,'9','9-5','AP Schedule'),
                                          (42,'8','8-4','Purchase Invoice Report'),
                                          (43,'4','4-5','Locations Management'),
                                          (44,'10','10-1','Fixed Asset Management'),
                                          (45,'9','9-9','Annual Income Statement'),
                                          (46,'6','6-8','Recurring Template'),
                                          (47,'9','9-10','VAT Relief Report'),
                                          (48,'1','1-6','Petty Cash Journal'),
                                          (49,'9','9-13','Replenishment Report'),
                                          (50,'6','6-9','Backup Database'),
                                          (51,'9','9-14','Book of Accounts'),
                                          (52,'9','9-16','Comparative Income'),
                                          (53,'4','4-6','Bank Reference Management'),
                                          (54,'10','10-2','Depreciation Expense Report'),
                                          (55,'11','11-1','Bank Reconciliation'),
                                          (57,'12','12-1','Voucher Registry Report'),
                                          (58,'12','12-2','Check Registry Report'),
                                          (59,'12','12-3','Collection List Report'),
                                          (60,'12','12-4','Open Purchase Report'),
                                          (61,'12','12-5','Open Sales Report'),
                                          (62,'9','9-11','Schedule of Expense'),
                                          (63,'9','9-15','AR Reports'),
                                          (64,'9','9-12','Cost of Goods'),
                                          (65,'13','13-1','Service Invoice'),
                                          (66,'13','13-2','Service Journal'),
                                          (67,'13','13-3','Service Unit Management'),
                                          (68,'13','13-4','Service Management'),
                                          (69,'9','9-17','Aging of Receivables'),
                                          (70,'9','9-18','Aging of Payables'),
                                          (71,'9','9-19','Statement of Account'),
                                          (72,'6','6-10','Email Settings'),
                                          (73,'14','14-1','Treasury'),
                                          (74,'9','9-20','Replenishment Batch Report'),
                                          (75,'9','9-21','General Ledger'),
                                          (76,'6','6-11','Email Report'),
                                          (77,'12','12-6','Product Reorder (Pick-list)'),
                                          (78,'12','12-7','Product List Report'),
                                          (79,'2','2-8','Purchase History'),
                                          (80,'2','2-7','Purchase Monitoring'),
                                          -- (81,'6','6-12','Puchasing Integration'),
                                          (82,'15','15-1','Product Management (Inventory Tab)'),
                                          (83,'3','3-4','Cash Invoice'),
                                          (84,'6','6-13','Audit Trail'),
                                          (85,'15','15-5','Item Transfer to Department'),
                                          (86,'15','15-6','Stock Card / Bin Card'),
                                          (87,'3','3-5','Warehouse Dispatching'),
                                          (88,'4','4-7','Brands'),
                                          (89,'16','16-1','Monthly Percentage Tax Return'),
                                          (90,'16','16-2','Quarterly Percentage Tax Return'),
                                          (91,'16','16-3','Certificate of Creditable Tax'),
                                          (92,'6','6-14','Statement of Accounts Settings'),
                                          (94,'17','17-1','Service Connection'),
                                          (95,'17','17-2','Service Disconnection'),
                                          (96,'17','17-3','Service Reconnection'),
                                          (97,'18','18-1','Meter Reading Entry'),
                                          (98,'18','18-2','Process Billing'),
                                          (99,'18','18-3','Billing Payments'),
                                          (100,'19','19-1','Charges Management'),
                                          (101,'19','19-2','Charge Unit Management'),
                                          (102,'19','19-3','Other Charges'),
                                          (103,'20','20-1','Residential Rate Matrix'),
                                          (104,'20','20-2','Commercial Rate Matrix'),
                                          (105,'20','20-3','Meter Inventory'),
                                          (106,'20','20-4','Meter Reading Period'),
                                          (107,'20','20-5','Attendant Management'),
                                          (108,'21','21-1','Service Trail'),
                                          (109,'21','21-2','Consumption History'),
                                          (110,'21','21-3','Billing Statement'),
                                          (111,'22','22-1','Billing Sending'),
                                          (112,'22','22-2','Payment Sending'),
                                          (113,'21','21-4','Customer Billing Subsidiary'),
                                          (114,'21','21-5','Batch Payment Report'),
                                          (115,'22','22-3','Connection Deposits Sending'),
                                          (116,'21','21-6','Batch Connection Deposits Report'),
                                          (117,'23','23-1','Create Service Connection'),
                                          (118,'23','23-2','Edit Service Connection'),
                                          (119,'23','23-3','Delete Service Connection'),
                                          (120,'24','24-1','Create Service Disconnection'),
                                          (121,'24','24-2','Edit Service Disconnection'),
                                          (122,'24','24-3','Delete Service Disconnection'),
                                          (123,'25','25-1','Create Service Reconnection'),
                                          (124,'25','25-2','Edit Service Reconnection'),
                                          (125,'25','25-3','Delete Service Reconnection'),
                                          (126,'26','26-1','Create Meter Reading Entry'),
                                          (127,'26','26-2','Edit Meter Reading Entry'),
                                          (128,'26','26-3','Delete Meter Reading Entry'),
                                          (129,'27','27-1','Process Billing Statement'),                                        
                                          (130,'28','28-1','Create Billing Payment'),
                                          (131,'28','28-2','Cancel Billing Payment'),
                                          (132,'29','29-1','Create Charges Management'),
                                          (133,'29','29-2','Edit Charges Management'),
                                          (134,'29','29-3','Delete Charges Management'),
                                          (135,'30','30-1','Create Charges Unit Management'),
                                          (136,'30','30-2','Edit Charges Unit Management'),
                                          (137,'30','30-3','Delete Charges Unit Management'),
                                          (138,'31','31-1','Create Other Charges'),
                                          (139,'31','31-2','Edit Other Charges'),
                                          (140,'31','31-3','Delete Other Charges'),
                                          (141,'32','32-1','Create Residential Rate Matrix'),
                                          (142,'32','32-2','Edit Residential Rate Matrix'),
                                          (143,'32','32-3','Delete Residential Rate Matrix'),
                                          (144,'33','33-1','Create Commercial Rate Matrix'),
                                          (145,'33','33-2','Edit Commercial Rate Matrix'),
                                          (146,'33','33-3','Delete Commercial Rate Matrix'),
                                          (147,'34','34-1','Create Meter Inventory'),
                                          (148,'34','34-2','Edit Meter Inventory'),
                                          (149,'34','34-3','Delete Meter Inventory'),
                                          (150,'35','35-1','Create Meter Reading Period'),
                                          (151,'35','35-2','Edit Meter Reading Period'),
                                          (152,'35','35-3','Delete Meter Reading Period'),
                                          (153,'35','35-4','Close Meter Reading Period'),
                                          (154,'36','36-1','Create Attendant Management'),
                                          (155,'36','36-2','Edit Attendant Management'),
                                          (156,'36','36-3','Delete Attendant Management'),
                                          (157,'37','37-1','Send to Accounting Connection Deposits'),
                                          (158,'38','38-1','Send to Accounting Billing'),
                                          (159,'39','39-1','Send to Accounting Payments'),
                                          (160,'21','21-7','Customer Billing Receivables'),
                                          (161,'21','21-8','Monthly Connection'),
                                          (162,'21','21-9','Penalties Incurred'),
                                          (163,'22','22-4','Penalty Sending'),
                                          (164,'20','20-6','Institutional Rate Matrix'),
                                          (165,'40','40-1','Create Institutional Rate Matrix'),
                                          (166,'40','40-2','Edit Institutional Rate Matrix'),
                                          (167,'40','40-3','Delete Institutional Rate Matrix')


                                          ON DUPLICATE KEY UPDATE
                                          rights_links.parent_code=VALUES(rights_links.parent_code),
                                          rights_links.link_code=VALUES(rights_links.link_code),
                                          rights_links.link_name=VALUES(rights_links.link_name)
            ";
        $this->db->query($sql);
    }
}
?>