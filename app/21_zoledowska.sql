-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Czas generowania: 17 Maj 2024, 17:07
-- Wersja serwera: 8.0.25
-- Wersja PHP: 8.3.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `21_zoledowska`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `Albums`
--

CREATE TABLE `Albums` (
  `album_id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
  `description` text CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `Comments`
--

CREATE TABLE `Comments` (
  `comment_id` int NOT NULL,
  `photo_id` int NOT NULL,
  `email` varchar(255) COLLATE utf8_polish_ci NOT NULL,
  `nick` varchar(45) COLLATE utf8_polish_ci NOT NULL,
  `content` text COLLATE utf8_polish_ci NOT NULL,
  `post_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `Photos`
--

CREATE TABLE `Photos` (
  `photo_id` int NOT NULL,
  `album_id` int NOT NULL,
  `user_id` int NOT NULL,
  `title` varchar(255) COLLATE utf8_polish_ci NOT NULL,
  `description` text COLLATE utf8_polish_ci NOT NULL,
  `photo_path` text CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
  `upload_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `Users`
--

CREATE TABLE `Users` (
  `user_id` int NOT NULL,
  `email` varchar(191) COLLATE utf8_polish_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_polish_ci NOT NULL,
  `roles` json NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_polish_ci;

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `Albums`
--
ALTER TABLE `Albums`
  ADD PRIMARY KEY (`album_id`);

--
-- Indeksy dla tabeli `Comments`
--
ALTER TABLE `Comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `photo_id` (`photo_id`);

--
-- Indeksy dla tabeli `Photos`
--
ALTER TABLE `Photos`
  ADD PRIMARY KEY (`photo_id`),
  ADD KEY `album_id` (`album_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeksy dla tabeli `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT dla zrzuconych tabel
--

--
-- AUTO_INCREMENT dla tabeli `Albums`
--
ALTER TABLE `Albums`
  MODIFY `album_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `Comments`
--
ALTER TABLE `Comments`
  MODIFY `comment_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `Photos`
--
ALTER TABLE `Photos`
  MODIFY `photo_id` int NOT NULL AUTO_INCREMENT;

--
--
-- AUTO_INCREMENT dla tabeli `Users`
--
ALTER TABLE `Users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT;

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `Comments`
--
ALTER TABLE `Comments`
  ADD CONSTRAINT `Comments_ibfk_1` FOREIGN KEY (`photo_id`) REFERENCES `Photos` (`photo_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ograniczenia dla tabeli `Photos`
--
ALTER TABLE `Photos`
  ADD CONSTRAINT `Photos_ibfk_1` FOREIGN KEY (`album_id`) REFERENCES `Albums` (`album_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `Photos_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
