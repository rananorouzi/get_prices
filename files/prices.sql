

--
-- Database: `backend_test`
--
CREATE DATABASE IF NOT EXISTS `backend_test` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `backend_test`;

-- --------------------------------------------------------
--
-- Table structure for table `alko_prices`
--

CREATE TABLE `alko_prices` (
  `numero` int(10) NOT NULL,
  `nimi` varchar(50) DEFAULT NULL,
  `pullokoko` varchar(20) DEFAULT NULL,
  `hinta` decimal(10,2) DEFAULT NULL,
  `priceGBP` decimal(10,2) DEFAULT NULL,
  `timestamp` int(11) DEFAULT NULL,
  `orderamount` int(11) DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Indexes for table `alko_prices`
--
ALTER TABLE `alko_prices`
  ADD PRIMARY KEY (`numero`);
COMMIT;
