<?php
class Teletype {
	private $doc = null;
	public function __construct () {
		$this->doc = new DOMDocument();
	}
	private function byXpath($query) {
		$xpath = new DOMXPath($this->doc);
		$entries = $xpath->query($query);
		return $entries;
	}
	public function getContents($link) {
		$opts = array (
			'http' => array (
				'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Safari/537.36;\nAccept-Language: ru-ru,ru;q=0.8,en-us;q=0.5,en;q=0.3"
			)
		);
		return file_get_contents($link, false, stream_context_create($opts));
	}
	public function getPost($link) {
		
		$this->doc->loadHTML($this->getContents($link));

		$title = $this->byXpath("//*[@id=\"content\"]/div/div[2]/div[1]/header/h1")[0]->nodeValue;
		$text = $this->byXpath("//*[@id=\"content\"]/div/div[2]/div[1]/article/p")[0]->nodeValue;
		$date = $this->byXpath("//*[@id=\"content\"]/div/div[2]/div[1]/div[2]/div[1]/div[1]")[0]->nodeValue;
		return [$title, $text, $date];
	}

	public function getPosts($link) {
		$this->doc->loadHTML($this->getContents($link));
		return $this->byXpath("//*[@class=\"article__header_link\"]");
	}
}
