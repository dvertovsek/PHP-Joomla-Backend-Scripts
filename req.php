<style>
.naziv
{
	color:gray;
}
.vrijednost
{
	
}
.glavni
{
	
}
</style>
<?php
$api_key = "...";

$natureOfContract = "public_works_contract";
$sortField = "publicationDate";
$maxResults = "5";
$region = "7091"; //Zadarska Zupanija

$url = "http://www.tenderi.hr/api/branch/tenders?apiKey=".$api_key."&format=json&natureOfContract=".$natureOfContract."&region=".$region."&sortField=".$sortField."&max=".$maxResults;
$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_URL, $url);
$data = curl_exec($ch);
curl_close($ch);
$json_objekt = json_decode($data); 
    
foreach($json_objekt->tenders as $ten)
{
	echo "<div class='glavni'>"; //jednostavno ga echoas (gore je njegovva klasa)
	echo "<h2><a target='_blank' href='".$ten->url."'>". $ten->title . "</a></h2>"; 
	echo "<div> <span class='naziv'> Lokacija: </span><span class='vrijednost'>".$ten->region."</span></div>";
	echo "<div> <span class='naziv'> Vrsta ugovora: </span><span class='vrijednost'>".$ten->natureOfContract."</span></div>"; 
	echo "<div> <span class='naziv'> Datum objave: </span><span class='vrijednost'>".$ten->publicationDate."</span></div>"; 
	echo "<div> <span class='naziv'> Rok za podnošenje ponuda: </span><span class='vrijednost'>".$ten->deadlineDate."</span></div>"; 
	echo "</div>";
} 
?>