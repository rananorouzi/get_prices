<?php

/**
 * Prices Class
 */
require_once(__DIR__ . '\Database.class.php');


class Prices extends Database
{

    /**
     * @param string $url
     * @param string $filePath
     * @param bool $addToDb
     * @return array
     */
    function curl(string $url, string $folder, string $fileName, bool $addToDb = false): bool
    {
        if (!empty($url) && !empty($fileName) && !empty($folder)) {
            //prices/
            $baseDir = dirname(__DIR__, 1);
            $debugPath = $baseDir . '/files/debug/curl-debug.log';
            $dir = $baseDir . '/files/' . $folder;
            $filePrice = $dir . '/' . $fileName;
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            if (file_exists($filePrice)) {
                return true;
            }
            $files = glob($dir . '/*');
            if (!empty($files)) {
                foreach ($files as $file) {
                    if (is_file($file))
                        @unlink($file);
                }
            }
            $ch = curl_init();
            $targetFile = fopen($filePrice, 'w');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_URL, trim($url));
            curl_setopt($ch, CURLOPT_AUTOREFERER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_FAILONERROR, true);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLINFO_HEADER_OUT, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Linux i686; rv:6.0) Gecko/20100101 Firefox/6.0Mozilla/4.0 (compatible;)");
            curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
            curl_setopt($ch, CURLOPT_ENCODING, '');
            curl_setopt($ch, CURLOPT_VERBOSE, true);
            curl_setopt($ch, CURLOPT_NOPROGRESS, true);
            curl_setopt($ch, CURLOPT_FILE, $targetFile);
            curl_exec($ch);
            if (!curl_errno($ch)) {
                switch (curl_getinfo($ch, CURLINFO_HTTP_CODE)) {
                    case 200:
                        break;
                    default:
                        file_put_contents($debugPath, '[' . date('Y/m/d - H:i:s') . '] - Curl error: ' . curl_error($ch) . PHP_EOL, FILE_APPEND);
                        return false;
                }
            }

            curl_close($ch);
            fclose($targetFile);

            if ($addToDb) {
                $this->addFileDataToDB($filePrice, $baseDir.'/files/currency/currencies-' . date('Ymd') . '.data');
            }
            return true;
        }
        return false;
    }

    /**
     * @param string $filePath
     * @param string $currencyPath
     * @return bool
     */
    function addFileDataToDB(string $filePath, string $currencyPath)
    {
        if (!file_exists($filePath) && !file_exists($filePath)) {
            return false;
        }
        $inputFileName = $filePath;
        /**  Create a new Reader of the type defined in $inputFileType  **/
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
        /**  Advise the Reader that we only want to load cell data  **/
        $reader->setReadDataOnly(true);
        /**  Load $inputFileName to a Spreadsheet Object  **/
        $spreadsheet = $reader->load($inputFileName);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();
        $finalData = [];
        $dti = new DateTimeImmutable();
        $timeStamp = $dti->getTimestamp();
        foreach ($rows as $priceData) {
            if (is_numeric($priceData[0]) && $priceData[0] > 0) {
                if ($this->find($priceData[0], 'numero', 'alko_prices')) {
                    $update = [
                        'hinta' => number_format((float)$priceData[4], 2, '.', ''),
                        'priceGBP' => $this->getPriceGBP($priceData[4], $currencyPath),
                        'timestamp' => $timeStamp,
                    ];
                    $where['numero'] = $priceData[0];
                    $this->update($update, 'alko_prices', $where);
                } else {
                    $finalData['numero'] = $priceData[0];
                    $finalData['nimi'] = $priceData[1];
                    $finalData['pullokoko'] = $priceData[3];
                    $finalData['hinta'] = number_format((float)$priceData[4], 2, '.', '');
                    $finalData['priceGBP'] = $this->getPriceGBP($priceData[4], $currencyPath);
                    $finalData['timestamp'] = $timeStamp;
                    $finalData['orderamount'] = 0;
                    $this->insert($finalData, 'alko_prices');
                }
            }
        }
        return true;
    }

    /**
     * @param $price
     * @param string $currencyPath
     * @return int
     */
    private function getPriceGBP($price, string $currencyPath)
    {
        if (!file_exists($currencyPath) || empty($price)) {
            return 0;
        }
        $currencies = JSON_DECODE(file_get_contents($currencyPath));
        if (is_numeric( $currencies->quotes->USDEUR) &&  $currencies->quotes->USDEUR > 0 && is_numeric( $currencies->quotes->USDGBP) &&  $currencies->quotes->USDGBP > 0) {
            $currenciesQuotesEUR = $currencies->quotes->USDEUR;
            $currenciesQuotesGBP = $currencies->quotes->USDGBP;
            $GBPPrice = ($price * ($currenciesQuotesGBP / $currenciesQuotesEUR));
            return number_format((float)$GBPPrice, 2, '.', '');
        }
        return 0;
    }
}