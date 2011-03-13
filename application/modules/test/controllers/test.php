<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test extends MX_Controller {

	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		$this->load->view('test_view');
	}
	
	function test2()
	{
		echo "Test Two";
	}
	
	function test3()
	{
		$this->load->library('javascripts');	
		
		$this->javascripts->add('test1.js');
		
		print_r($this->javascripts->get());
	}
	
	function test4()
	{
		$this->load->library('administration');
		
	}
	
	
	function test5()
	{
		
		
		$primes = array(2,5,7,11,13,17,19,23,29,31,37,41,43,47);
		print_r($primes);
		
		for($n=1; $n < 200; $n++)
		{
		  if($n)
		  {
			  for($i=0; $i < count($primes); $i++)
			  {
				  if($primes[$i] > sqrt($n))
				  {
					  if($i > 0)
					  {
					  	$msg = "N: ". ($n += $primes[$i-1])."<br>";
					  }
					  else
					  {
						  $msg = "N: ". ($n += $primes[$i])."<br>";
					  }
					  
					  break;
				  }
			  }
			  
			  if(empty($msg))
			  {
				  $msg = "N: $n, ".($n += 1)."<br>";	
			  }
			  
		  }
		  else
		  {
			  $msg =  "Error: Number not valid";
		  }
		  
		  echo $msg;
		}
	}
	
	
	function makePrimes()
	{
		$primes = array();
		
		$count = 0 ;  
		$number = 2 ;  
		while ($count < 2500 )  
		{  
			$div_count=0;  
			for ( $i=1;$i<=$number;$i++)  
			{  
				if (($number%$i)==0)  
				{  
					$div_count++;  
				}  
			}  
			
			if ($div_count< 3)  
			{  
				$primes[] = $number;  
				$count=$count+1;  
			}  
			
			$number=$number+1;  
		}  	
	
		$idx = 0;
		while($idx < count($primes))
		{
			echo $primes[$idx].", ";
			if(($idx%20)==0)
			{
				echo "<br />";
			}
			$idx++;
		}
		
	}
	
	
	function fibonacci()
	{
		//$rnd = rand(100);
		//$len = rand(100);
		$n = 500;
		$last = 1;
		$slast = 0;
		$numbers = array();
		
		$numbers[0] = 1;
		
		for($i = 1; $i < $n; $i++)
		{
			$numbers[$i] = $last + $slast;
			$slast = $last;
			$last = $numbers[$i];
		}
		
		foreach($numbers as $idx => $val)
		{
			echo $val.', ';
			if($idx AND ($idx % 20)==0)
			{
				echo "<br />";	
			}
		}
	}
	

	function series()
	{
		$this->load->library('seriesgen');
		
		$param[] = $this->seriesgen->newToken();
		
		for($i=1; $i <= 5000; $i++)
		{
			$param[] = $this->seriesgen->updateToken($param[$i-1]);
		}
		 	
		foreach($param as $tok)
		{
			if($this->seriesgen->validateToken($tok))
			{
				echo "Valid token: ";
			}
			print_r($tok);
			echo "<br>";
			
		}
	}
}

?>