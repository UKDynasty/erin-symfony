<?php
namespace App\Service\MFL;

use App\Entity\Franchise;

class UrlProvider
{
    /**
     * @var int
     */
    private $mflYear;
    /**
     * @var string
     */
    private $mflLeagueId;

    public function __construct(int $mflYear, string $mflLeagueId)
    {
        $this->mflYear = $mflYear;
        $this->mflLeagueId = $mflLeagueId;
    }

    public function franchiseRoster(Franchise $franchise)
    {
        return sprintf(
            'https://www80.myfantasyleague.com/%s/options?L=%s&F=%s&O=07',
            $this->mflYear,
            $this->mflLeagueId,
            $franchise->getMflFranchiseId()
        );
    }
}