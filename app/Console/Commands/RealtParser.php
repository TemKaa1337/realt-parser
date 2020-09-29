<?php

declare(strict_types = 1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use KubAT\PhpSimple\HtmlDomParser;
use App\Models\Advertisment;

class RealtParser extends Command
{
    /**
     * Target parse url.
     *
     * @var string
     */
    public string $parseUrl = 'https://realt.by/rent/flat-for-day/';

    /**
     * Target parse url.
     *
     * @var int
     */
    public int $numberOfPagesToParse = 1;

    /**
     * Xpath of parsed html.
     *
     * @var object
     */
    private $dom;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:realt {--start_page_number=1}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() : int
    {
        $this->setParseParams();
        
        // iterate through advertisments
        foreach ($this->dom->find('.bd-item') as $advertisment) {
            $ad = new Advertisment();

            // $header = $advertisment->find('.media-body', 0)->find('a', 0)->plaintext;
            // $link = $advertisment->find('.media-body', 0)->find('a', 0)->href;
            // $postHref = $advertisment->find('.media-body', 0)->find('a', 0)->plaintext;
            // $imageHref = $advertisment->find('.bd-item-left-top', 0)->find('img', 0)->getAttribute('data-original');
            // // мб тут и не надо убирать
            // // $price = str_replace('&nbsp;', ' ', $advertisment->find('span[class=price-byr]', 0)->plaintext);
            // $price = $advertisment->find('span[class=price-byr]', 0)->plaintext;
            // $postedAt = $this->formatPostedDate($advertisment->find('p[class=fl f11 grey]', 0)->plaintext);
            // $location = $advertisment->find('div[class=bd-item-right-center]', 0)->find('p', 0)->plaintext;
            // $description = $advertisment->find('div[class=bd-item-right-center]', 0)->find('p', 1)->plaintext;
            
            
            $ad->header = $advertisment->find('.media-body', 0)->find('a', 0)->plaintext;
            $ad->link = $advertisment->find('.media-body', 0)->find('a', 0)->href;
            $ad->image_path = $advertisment->find('.bd-item-left-top', 0)->find('img', 0)->getAttribute('data-original');
            $ad->price = $advertisment->find('span[class=price-byr]', 0)->plaintext;
            $ad->posted_at = $this->formatPostedDate($advertisment->find('p[class=fl f11 grey]', 0)->plaintext);
            $ad->location = $advertisment->find('div[class=bd-item-right-center]', 0)->find('p', 0)->plaintext;
            $ad->description = $advertisment->find('div[class=bd-item-right-center]', 0)->find('p', 1)->plaintext;
            $ad->save();
        }
        
        return 0;
    }

    private function setParseParams() : void
    {
        $startPageNumber = $this->option('start_page_number') - 1;

        if ($startPageNumber !== 0) {
            $this->parseUrl .= "?page=$startPageNumber";
        }

        $this->dom = HtmlDomParser::file_get_html($this->parseUrl);
    }

    public function formatPostedDate(string $date) : string
    {
        return date('Y-m-d', strtotime(trim($date)));
    }
}
