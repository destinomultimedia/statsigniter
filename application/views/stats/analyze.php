<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
	<head>
		<title>StatsIgniter - Analyze</title>
		<meta name="author" content="David López Santos"/>		
		<!-- CSS -->
		<link rel="stylesheet" href="<?php echo base_url();?>assets/css/jquery-ui-1.8.20.custom.css" />
        <link rel="stylesheet" href="<?php echo base_url();?>assets/css/jquery.dataTables.css" />
        <link rel="stylesheet" href="<?php echo base_url();?>assets/css/jquery.snippet.min.css" />
        <link rel="stylesheet" href="<?php echo base_url();?>assets/css/style.css" />
        <!-- JS -->
		<script src="<?php echo base_url();?>assets/js/jquery-1.7.1.min.js"></script>
        <script src="<?php echo base_url();?>assets/js/jquery-ui-1.8.20.custom.min.js"></script>
        <script src="<?php echo base_url();?>assets/js/jquery.dataTables.min.js"></script>
        <script src="<?php echo base_url();?>assets/js/jquery.snippet.min.js"></script>
	</head>
	<body style="padding: 5% 10%;">
    <div id="logo_dm">
        <a href="http://www.destinomultimedia.com" target="_blank"><img src="<?php echo base_url();?>assets/img/logo_dm.png" alt="Destino Multimedia"/></a>
    </div>
    <div id="logo_si">
        <a href="http://www.destinomultimedia.com/opensource/statsigniter" target="_blank"><img src="<?php echo base_url();?>assets/img/logo_si.png" alt="StatsIgniter"/></a>
    </div>
    <div class="clear"></div>
    <div id="tab-container">
        <ul>
    		<li><a href="#tabs-1"><?php echo $language['tab_file'];?></a></li>
    		<li><a href="#tabs-2"><?php echo $language['tab_extension'];?></a></li>
    	</ul>
    	<div id="tabs-1">
    		<table id="table_files" style="border: 1px solid Gray;">
                <thead>
                    <th><?php echo $language['header_extension'];?></th>
                    <th><?php echo $language['header_nombre'];?></th>
                    <th><?php echo $language['header_ruta'];?></th>
                    <th><?php echo $language['header_num_lineas'];?></th>
                </thead>
                <tbody>
                <?php
                    $line_counter = 0;
                    $arr_types = array();
                
                    foreach($arr_files AS $file){
                        $extension = strtoupper($file['extension']);
                        
                        //Count filetypes
                        if(array_key_exists($extension, $arr_types)){
                            $item = $arr_types[$extension];
                            $item['num_files'] = $item['num_files'] + 1;
                            $item['num_lines'] = $item['num_lines'] + $file['num_lines'];
                        }
                        else{
                            $item = array();
                            $item['num_files'] = 1;
                            $item['num_lines'] = $file['num_lines'];
                        }
                        
                        $arr_types[$extension] = $item;
                        ?>
                        <tr>
                            <td><?php echo $extension;?></td>
                            <td><?php echo $file['name'];?></td>
                            <td><a href='#' title="<?php echo strtolower($file['extension']);?>" name="<?php echo urlencode($file['path']);?>" class="open_dialog"><?php echo $file['path'];?></a></td>
                            <td style="text-align: right;"><?php echo $file['num_lines'];?></td>
                        </tr>
                        <?php
                        $line_counter = $line_counter + $file['num_lines'];
                    }
                ?>
                </tbody>
            </table>
             <table id="table_total" style="border: 1px solid Gray;" class="dataTable">
                <tbody>
                    <tr class="odd">
                        <td style="text-align: right;"><strong><?php echo $language['footer_total_lineas'];?>:</strong></td>
                        <td style="text-align: right;width: 100px"><?php echo $line_counter;?></td>
                    </tr>
                </tbody>
            </table>
    	</div>
    	<div id="tabs-2">
    		<table id="table_types" style="border: 1px solid Gray;">
                <thead>
                    <th><?php echo $language['header_extension'];?></th>
                    <th><?php echo $language['header_num_files'];?></th>
                    <th><?php echo $language['header_num_lineas'];?></th>
                </thead>
                <tbody>
                <?php
                    foreach($arr_types AS $file_type=>$item){
                    ?>
                    <tr>
                        <td><?php echo strtoupper($file_type);?></td>
                        <td style="text-align: right;"><?php echo $item['num_files'];?></td>
                        <td style="text-align: right;"><?php echo $item['num_lines'];?></td>
                    </tr>
                    <?php
                    }
                ?>
                </tbody>
            </table>
             <table id="table_total" style="border: 1px solid Gray;" class="dataTable">
                <tbody>
                    <tr class="odd">
                        <td style="text-align: right;"><strong><?php echo $language['footer_total_lineas'];?>:</strong></td>
                        <td style="text-align: right;width: 100px"><?php echo $line_counter;?></td>
                    </tr>
                </tbody>
            </table>
    	</div>
    </div>
    <div id="div_dialog" title="Source"></div>
    </body>
    <script language="javascript">
    $(document).ready(function () {
        //Create Tabs
        $( "#tab-container" ).tabs();
        
        //Create dialog
        $('#div_dialog').dialog({
            autoOpen: false,
            width: 800,
            height: 400
        });
        
        //Open dialog
        $('.open_dialog').click(function(){
            $.ajax({
                type: 'POST',
                url: '<?php echo base_url();?>stats/ajax_load_code',
                data: {
                    file: this.name,
                    type: this.title
                },
                success: function(data) {
                    $('#div_dialog').html(data);
                }
            });
            $('#div_dialog').dialog('open');    
        });
        
        //Create Tabs
        
        //Create dataTable
    	$('#table_files').dataTable({
    	   "bJQueryUI": true,
    	   "aaSorting": [[ 1, "asc" ]],
           "iDisplayLength": 25,
           "bStateSave": true,
           "sPaginationType": "full_numbers",
           "oLanguage": {
                "sInfoFiltered": "<?php echo $language['footer_filtered'];?>",
                "sInfo": "<?php echo $language['footer_showing'];?>",
                "sLengthMenu": "<?php echo $language['table_show'];?>",
                "sSearch": "<?php echo $language['table_search'];?>",
                "oPaginate": {
                    "sFirst": "<?php echo $language['footer_first'];?>",
                    "sLast": "<?php echo $language['footer_ultima'];?>",
                    "sNext": "<?php echo $language['footer_siguiente'];?>",
                    "sPrevious": "<?php echo $language['footer_previo'];?>"
                }
            }
    	});
        
        //Create dataTable
    	$('#table_types').dataTable({
    	   "bJQueryUI": true,
    	   "aaSorting": [[ 1, "asc" ]],
           "iDisplayLength": 25,
           "bStateSave": true,
           "sPaginationType": "full_numbers",
           "oLanguage": {
                "sInfoFiltered": "<?php echo $language['footer_filtered'];?>",
                "sInfo": "<?php echo $language['footer_showing'];?>",
                "sLengthMenu": "<?php echo $language['table_show'];?>",
                "sSearch": "<?php echo $language['table_search'];?>",
                "oPaginate": {
                    "sFirst": "<?php echo $language['footer_first'];?>",
                    "sLast": "<?php echo $language['footer_ultima'];?>",
                    "sNext": "<?php echo $language['footer_siguiente'];?>",
                    "sPrevious": "<?php echo $language['footer_previo'];?>"
                }
            }
    	});
        
        //CodeViewer
        $("pre.html").snippet("html");
        $("pre.php").snippet("php");
        $("pre.css").snippet("css");
        $("pre.js").snippet("js");
    });
    </script>
 </html>