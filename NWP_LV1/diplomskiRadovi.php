<!DOCTYPE html>

<head></head>

<body>
    <?php
    require_once './iRadovi.php';
    include("./simple_html_dom.php");//dodatak za lakše parsanje HTML DOM-a
    
    class DiplomskiRadovi implements iRadovi
    {
        private $naziv_rada;
        private $tekst_rada;
        private $link_rada;
        private $oib_tvrtke;

        protected $servername = "localhost";
        protected $username = "root";
        protected $password = "";
        protected $databasename = "diplomski_radovi";

        function __construct($data)
        {
            if($data !== NULL)
                $this->create($data);
        }

        //ZADATAK 2
        public function create($data)
        {
            $this->naziv_rada = $data['naziv_rada'];
            $this->tekst_rada = $data['tekst_rada'];
            $this->link_rada = $data['link_rada'];
            $this->oib_tvrtke = $data['oib_tvrtke'];
        }

        public function save()
        {
            $conn = new mysqli($this->servername, $this->username, $this->password, $this->databasename);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "INSERT INTO `radovi`(`naziv_rada`, `tekst_rada`, `link_rada`, `oib_tvrtke`) VALUES ('{$this->naziv_rada}', '{$this->tekst_rada}', '{$this->link_rada}', '{$this->oib_tvrtke}')";

            $result = $conn->query($sql);
            if ($result) {
                //nesto napraviti ubuduce
            } else {
                echo "Error in dbms: " . $conn->error;
            }
            $conn->close();
        }

        public function read()
        {
            $conn = new mysqli($this->servername, $this->username, $this->password, $this->databasename);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            $sql = "SELECT * FROM `radovi`";
            $result_html = "";
            $result = $conn->query($sql);
            var_dump($result->num_rows);//remove me

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $result_html .= "<p> {$row['id']} </p>";
                    $result_html .= "<p> {$row['naziv_rada']} </p>";
                    $result_html .= "<p> {$row['tekst_rada']} </p>";
                    $result_html .= "<a href='{$row['link_rada']}'> {$row['link_rada']} </a>";
                    $result_html .= "<p> {$row['oib_tvrtke']} </p>";
                    $result_html .= "<br><br>";
                }
                echo $result_html;
            }
            $conn->close();
        }
    }

    
    //ZADATAK 1
    $page_num = 3;
    $url = "http://stup.ferit.hr/index.php/zavrsni-radovi/page/$page_num";
    $url_opened = fopen($url, 'r');

    $read = file_get_html($url);
    foreach ($read->find('article') as $article) {
        //pronađi element article i radi dalje sa svakim od njih
        $image = $article->find('ul.slides img')[0]; //nadji sliku u artiklu
        $image_source = $image->src;

        $link = $article->find('h2.entry-title a')[0]; //nadji link i u isto vrijeme title
        $html = file_get_html($link->href); //otvori link
        $html_targeted_content = "";
        foreach ($html->find('.post-content') as $target_content) {
            $html_targeted_content .= $target_content->plaintext;
        }

        $diplomski_rad = array(
            'naziv_rada' => $link->plaintext,
            'tekst_rada' => $html_targeted_content,
            'link_rada' => $link->href,
            'oib_tvrtke' => preg_replace('/[^0-9]/', '', $image_source)
        );

        $novi_rad = new DiplomskiRadovi($diplomski_rad);
        $novi_rad->save();
    }

    $dummy_rad = new DiplomskiRadovi(NULL);
    $dummy_rad->read();

    ?>
</body>

</html>