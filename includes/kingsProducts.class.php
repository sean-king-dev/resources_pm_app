<?php
 session_start();
// kings stock Class

class kingsProducts {

   var $allprod = '';
   var $location = '';
   
   function kingsProducts(){
    $ClientClass = new kingsUser();

    if($_SESSION['user']['client_id']){
      $client = $ClientClass->getClient($_SESSION['user']['client_id']);
      $this->location = $client[0]->country;
	  $sql = "SELECT * FROM `products` WHERE ". $this->location."  !='0' and ". $this->location." !='' order by name";
	       $results = mysql_query( $sql );
			  while( $prod = mysql_fetch_object( $results ) ) {
		  $this->allprod[$prod->id]= $prod->name;
		  }
	       $this->allprod[0]= 'Please select';
      }
    }
    
    public function selectSupplierEmail($id){
     $sql = "SELECT * FROM `suppliers` where id = ".$id;
      $results = mysql_query( $sql );
      if($results){
       while( $prod2 = mysql_fetch_object( $results ) ) {
	  $prodArray= $prod2;
	}
      }
     return $prodArray->email;
    }
    
    public function getSupplName($id){
     $sql = "SELECT * FROM `suppliers` where id = ".$id;
      $results = mysql_query( $sql );
      if($results){
       while( $prod2 = mysql_fetch_object( $results ) ) {
	  $prodArray= $prod2;
	}
      }
	return $prodArray->Name;
    }
    
    public function getSuppler($id){
     $sql = "SELECT * FROM `suppliers` where id = ".$id;
      $results = mysql_query( $sql );
      if($results){
       while( $prod2 = mysql_fetch_object( $results ) ) {
	  $prodArray[]= $prod2;
	}
      }
	return $prodArray;
    }
    
    public function getSuppliers(){
     $sql = "SELECT * FROM `suppliers`";
      $results = mysql_query( $sql );
	while( $prod2 = mysql_fetch_object( $results ) ) {
	  $prodArray[]= $prod2;
	}
	return $prodArray;
    }
    
    public function getAllProducts(){
      $sql = "SELECT * FROM `products`";
      $results = mysql_query( $sql );
	while( $prod2 = mysql_fetch_object( $results ) ) {
	  $prodArray[]= $prod2;
	}
	return $prodArray;
    }
    
    public function getSingleProd($pid){
     $sql = "SELECT * FROM `products` where id = ".$pid;
      $results = mysql_query( $sql );
	if($results){
	 while( $prod2 = mysql_fetch_object( $results ) ) {
	   $prodArray= $prod2;
	 }
	 $prodArray->quantitites = $this->getProductQuantities($pid);
	}
	return $prodArray;
    }
    
    public function getProductQuantities($pid){
     $sql = "SELECT * FROM `product_quantity_map` where product_id = ".$pid;
      $results = mysql_query( $sql );
	if($results){
	 while( $prod2 = mysql_fetch_object( $results ) ) {
	   $prodArray[]= $prod2;
	 }
	}
	return $prodArray;
    }
    
    public function getQuantities(){
      $sql = "SELECT * FROM `quantities";
      $results = mysql_query( $sql );
	while( $prod2 = mysql_fetch_object( $results ) ) {
	  $qArray[]= $prod2;
	}
	return $qArray;
    }


}        
?>
