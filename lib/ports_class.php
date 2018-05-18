<?php


	class Ports_class {
		
		
		public function getAvailablePorts( ) {
			
			$available_ports = [];
			
			//$ports_query = select_query("mod_ports_ipaddress_ports" , "" , ['status' => 0] , 'id' , 'ASC');
			$ports_query = full_query("SELECT mpip.id, mpip.startport as port, mpi.ipaddress as ipaddress, mpip.status, mpip.package FROM mod_ports_ipaddress_ports mpip, mod_ports_ipaddress mpi WHERE mpip.ipaddress_id = mpi.id AND mpip.status = 0 ORDER BY mpip.id ASC");
			while($ports = mysql_fetch_array($ports_query , MYSQL_ASSOC)){
				$available_ports[] = ['id' => $ports['id'] , 'ipaddress' => $ports['ipaddress'] , 'port' => $ports['port'], 'status' => $ports['status'], 'package' => $ports['package']];
			}
			
			return $available_ports;

		}
		
		
		public function reservePort( $portid , $serviceid ) {
				
			update_query('mod_ports_ipaddress_ports', [ 'status' =>  1 , 'package' =>  $serviceid ], [ 'id' => $portid]);
			return;
				
		}
		
		public function unreservePorts( $serviceid ) {
			
			update_query('mod_ports_ipaddress_ports', [ 'status' =>  0 , 'package' =>  0 ], [ 'package' => $serviceid ]);
			return;
		}
		
		public function getreservePorts( $serviceid ) {
		
			
			$reserved_ports = [];
			
			//$ports_query = select_query("mod_ports_ipaddress_ports" , "" , ['status' => 0] , 'id' , 'ASC');
			$ports_query = full_query("SELECT mpip.id, mpip.startport as port, mpi.ipaddress as ipaddress, mpip.status, mpip.package FROM mod_ports_ipaddress_ports mpip, mod_ports_ipaddress mpi WHERE mpip.ipaddress_id = mpi.id AND mpip.status = 1 AND mpip.package = '" . $serviceid . "' ORDER BY mpip.id ASC");
			while($ports = mysql_fetch_array($ports_query , MYSQL_ASSOC)){
				$reserved_ports[] = ['id' => $ports['id'] , 'ipaddress' => $ports['ipaddress'] , 'port' => $ports['port'], 'status' => $ports['status'], 'package' => $ports['package']];
			}
			
			return $reserved_ports;
		}
		
		public function setQuantity( $serviceid , $quantity ) {
			
			return insert_query("mod_ports_package_quantity" , ['package' => $serviceid , 'qty' => $quantity]);
		}
		
		public function removeQuantity( $serviceid ) {
			
			return full_query("DELETE from mod_ports_package_quantity WHERE package = '" . $serviceid . "'");
		}
		
		
		public function getQuantity( $serviceid ) {
			
			$qty_query = select_query("mod_ports_package_quantity" , "qty" , ['package' => $serviceid]);
			$qty = mysql_fetch_array($qty_query , MYSQL_ASSOC);
			return $qty['qty'];
		}
		
	}


?>