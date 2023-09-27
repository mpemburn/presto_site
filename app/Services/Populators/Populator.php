<?php

namespace App\Services\Populators;

use App\Helpers\CsvHelper;
use App\Helpers\SqlHelper;
use App\Interfaces\PopulatorInterface;
use App\Services\SiteInfoService;

abstract class Populator implements PopulatorInterface
{
    protected SiteInfoService $infoService;
    protected array $replace = [];
    protected string $key;
    protected string $tableName;
    protected string $basePath;

    abstract protected function doReplacements(): void;

    public function __construct(string $key, SiteInfoService $infoService)
    {
        $this->infoService = $infoService;
        $this->tableName = $infoService->currentTable;
        $this->key = $key;
        $this->basePath = base_path() . '/app/Data/';

        $this->doReplacements();
        $this->readCsv();
    }

    public function readCsv()
    {
        $csvPath = $this->basePath . 'wp_' . $this->key . '.csv';
        $csvData = CsvHelper::read($csvPath);
        collect($csvData)->each(function ($row) {
            if ($row['option_value'] === 'placeholder') {
                $row['option_value'] = $this->replace[$row['option_name']];
            }

            SqlHelper::insert($row, $this->tableName);
        });
    }
}
