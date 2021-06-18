<?php

declare(strict_types = 1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use KubAT\PhpSimple\HtmlDomParser;
use App\Models\Advertisment;

class Parser extends Command
{
    /**
     * Target parse url.
     *
     * @var string
     */
    public string $parseUrl = 'https://realt.by/rent/flat-for-day/';

    /**
     * Dom of parsed html.
     *
     * @var object
     */
    private $dom;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:realt {--startPageNumber=1} {--pageCount=1} {--pageSleep=2} {--innerPageSleep=2}';

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
        for (
            $i = 0;
            $i < $this->option('pageCount');
            $i ++
        ) {
            $this->retrieveData($i);

            foreach ($this->dom->find('.listing-item') as $advertisment) {
                $link = $advertisment->find('.image.mb-0', 0)->href ?? null;

                if ($link !== null) {
                    $this->broadcastProcessingMessage("processing {$link}...");

                    $ad = Advertisment::firstOrNew(['link' => $link]);

                    $ad->link = $link;
                    $additionalInfo = $this->parseSingleAd($ad->link);
                    $ad->phones = $additionalInfo['phones'];
                    $ad->emails = $additionalInfo['emails'];
                    $ad->header = trim($advertisment->find('.teaser-title', 0)->plaintext);
                    $imageUrl = $advertisment->find('.lazy', 0)->getAttribute('data-original');
                    $ad->image_path = Str::startsWith($imageUrl, 'https') ? $imageUrl : 'https://realt.by/'.$advertisment->find('.lazy', 0)->getAttribute('data-original');
                    $ad->byn_price = $this->getPrice($advertisment->find('.d-flex.align-items-center.color-black.fs-huge', 0)->plaintext ?? null);
                    $ad->usd_price = $this->getPrice($advertisment->find('.col-auto', 0)->plaintext ?? null);

                    $info = $advertisment->find('.info-mini.color-graydark', 0)->find('span');

                    $index = count($info) - 2;

                    $ad->posted_at = $this->formatPostedDate($info[$index]->plaintext);

                    $ad->description = str_replace('&quot;', '"', trim($advertisment->find('.info-text.info-more', 0)->find('p', 0)->plaintext ?? $advertisment->find('.info-text.info-more', 0)->find('li', 0)->plaintext));
                    $ad->location = trim($advertisment->find('.location.color-graydark', 0)->plaintext);
                    $ad->room_count = $this->getRoomCount($advertisment->find('.info-large', 0)->find('span', 0)->plaintext);

                    if ($ad->image_path !== null) {
                        $imageName = explode('/', $ad->image_path);
                        Storage::disk('public')->put($imageName[count($imageName) - 1], file_get_contents($ad->image_path));

                        $ad->image_path = asset('storage/'.$imageName[count($imageName) - 1]);
                    }

                    $ad->save();
                } else {
                    $this->broadcastProcessingMessage("skipping, this is an advertisment...");
                }
            }

            sleep(intval($this->option('pageSleep')));
        }

        return 0;
    }

    private function formatPostedDate(string $date) : string
    {
        return date('Y-m-d', strtotime(trim($date)));
    }

    private function getPrice(?string $price) : ?int
    {
        if ($price === null) return null;

        $resultPrice = '';
        $price = trim($price);

        for ($i = 0; $i < strlen($price); $i ++) {
            if (is_numeric($price[$i]))
                $resultPrice .= $price[$i];
            else break;
        }

        return intval($price);
    }

    private function retrieveData(int $page) : void
    {
        if ($page == 0) {
            if ($this->option('startPageNumber') == 1) {
                // ne trogaem
                $url = $this->parseUrl;
            } else {
                $startPage = $this->option('startPageNumber') - 1 + $page;
                $url = $this->parseUrl."?page={$startPage}";
            }
        } else {
            $startPage = $this->option('startPageNumber') - 1 + $page;
            $url = $this->parseUrl."?page={$startPage}";
        }

        $this->broadcastProcessingMessage("retrieving data from {$url}...");
	
        // $this->dom = HtmlDomParser::str_get_html(file_get_contents($url));
        $this->dom = HtmlDomParser::file_get_html($url);
    }

    private function getRoomCount(string $rooms) : int
    {
        $roomCount = '';
        $rooms = trim($rooms);

        for ($i = 0; $i < strlen($rooms); $i ++) {
            if (is_numeric($rooms[$i]))
                $roomCount .= $rooms[$i];
            else break;
        }

        return intval($roomCount);
    }

    private function parseSingleAd(string $url) : array
    {
        sleep(intval($this->option('innerPageSleep')));

        $this->broadcastProcessingMessage("retrieving data from inner {$url}...");

        $phones = [];
        $emails = [];

        $dom = HtmlDomParser::str_get_html(file_get_contents($url));

        $contacts = $dom->find('.object-contacts.mb-10', 0)->find('a');

        foreach ($contacts as $contact) {
            $contact = trim($contact->plaintext);

            $isPhone = $this->isPhone($contact);

            if ($isPhone)
                $phones[] = $contact;
            else
                $emails[] = $contact;
        }

        return ['phones' => json_encode($phones), 'emails' => json_encode($emails)];
    }

    private function isPhone(string $contact) : bool
    {
        return strpos($contact, '@') === false;
    }

    private function broadcastProcessingMessage(string $message) : void
    {
        echo $message.PHP_EOL;
    }
}
