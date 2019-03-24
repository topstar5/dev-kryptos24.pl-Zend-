/*
SQLyog Community v12.4.3 (64 bit)
MySQL - 10.1.13-MariaDB 
*********************************************************************
*/
/*!40101 SET NAMES utf8 */;

create table `menu` (
	`id` int (11),
	`label` varchar (765),
	`path` varchar (765),
	`icon` varchar (765),
	`rel` varchar (765),
	`parent_id` int (11),
	`activate-routes` varchar (765)
); 
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('1','Strona główna','/home','icon-home','home',NULL,NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('2','Komunikacja','javascript:;','icon-chat-1','messages',NULL,NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('3','Pracownicy','javascript:;','fa fa-users','people',NULL,NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('4','Dokumenty','javascript:;','icon-book-open-1','documents',NULL,NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('5','Zamówienia publiczne','/public-procurements','icon-archive','public',NULL,NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('6','Zbiory','javascript:;','icon-archive','store',NULL,NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('7','Raporty','/reports','icon-print','report',NULL,NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('8','Rejestry','/registry','glyphicon glyphicon-th-large','report',NULL,'registry-entries');
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('9','Zasoby Informatyczne','javascript:;','fa fa-floppy-o','informatyk',NULL,NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('10','Czynności przetwarzania','javascript:;','glyphicon glyphicon-transfer','data_transfers',NULL,NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('11','Analiza ryzyka','javascript:;','glyphicon glyphicon-transfer','data_transfers',NULL,NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('12','Dane rejestracji','javascript:;','glyphicon glyphicon-dashboard','data_transfers',NULL,NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('13','Szkolenia','javascript:;','fa fa-graduation-cap','data_transfers',NULL,NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('14','Podpisy','/signatures','fa fa-bank','home',NULL,NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('15','Przeglądy','/inspections','fa fa-calendar-check-o','home',NULL,NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('16','Dysk online','/file-sources','fa fa-file','home',NULL,NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('17','Zdarzenia','javascript:;','icon-shuffle-2','events',NULL,NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('18','Zadania','/tasks','glyphicon glyphicon-tasks','tasks',NULL,NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('19','Aplikacja','javascript:;','icon-wrench','configuration',NULL,NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('20','Profil','javascript:;','icon-user','profile',NULL,NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('21','Wiadomości','/messages','icon-mail-2','messages','2',NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('22','Komunikaty','/kominfoadm','icon-website','admin','2',NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('23','Zgłoszenia','/tickets','fa fa-umbrella','tickets','2',NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('24','Moje zadania','/tasks-my','icon-th-thumb-empty','tasks','2',NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('25','Rejestr Osób','/osoby','fa fa-users','people','3',NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('26','Uprawnienia','/permissions','fa fa-users','people','3',NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('27','Konta bankowe','/osoby/kontabankowe','glyphicon glyphicon-usd','people_bankaccounts','3',NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('28','Podpisy elektroniczne','/osoby/podpisy','glyphicon glyphicon-pencil','people_signs','3',NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('29','Inne osoby','/osoby-inne','fa fa-users','people','3',NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('30','Grupy osób','/groups','fa fa-list-ol','groups','3',NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('31','Dokumenty wersjonowane','/documents-versioned','icon-docs','documents_versioned','4',NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('32','Dokumentacja osobowa','/documents','icon-book-open-1','documents','4',NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('33','Wszystkie dokumenty','/documents/all','icon-box-1','documents_all','4',NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('34','Dokumenty oczekujące','/documents/pending','fa fa-clock-o','documents','4',NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('35','Szablony dokumentów','/documenttemplates','fa fa-file-o','documenttemplates','4',NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('36','Schematy numeracji','/numberingschemes','fa fa-sort-numeric-asc','numberingschemes','4',NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('37','Rejestr zbiorów','/zbiory','icon-archive','store','6',NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('38','Rejestr zbiorów GIODO','/giodo','icon-upload-3','giodo','6',NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('39','Rejestry publiczne','/public-registry','icon-archive','public','6',NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('40','Systemy teleinformacyjne','/systemy-teleinformacyjne','icon-archive','public','6',NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('41','Elementy zbioru','/fielditems','fa fa-book','store','6',NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('42','Pola','/fields','icon-th-list-1','store','6',NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('43','Podmioty','/persons','icon-users','store','6',NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('44','Akty prawne','/legalacts','icon-section-sign','store','6',NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('45','Zabezpieczenia','/zabezpieczenia','icon-key-inv','saferules','6',NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('46','Pomieszczenia i budynki','/pomieszczenia','icon-home-circled','pomieszczenia','6',NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('47','Partnerzy','/contacts','icon-users','contacts','6',NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('48','Typy osób','/persontypes','icon-cc-by','store','6',NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('49','Kategorie elementów','/fielditemscategories','icon-folder-empty-1','store','6',NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('50','Kategorie pól','/fieldscategories','fa fa-list-ol','store','6',NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('51','Rejestr zmian w zbiorach','/zbiory-changelog','fa fa-list-ol','store','6',NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('52','Rejestr rozmów','/registry-phone-calls','glyphicon glyphicon-earphone','registry_phone_calls','8',NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('53','Wykaz aplikacji','/aplikacje','icon-clipboard','application','9',NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('54','Wykaz modułów','/aplikacje-moduly','icon-clipboard','application','9',NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('55','Sprzęt komputerowy','/computer','icon-print','computer','9',NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('56','Kopie zapasowe','/kopiezapasowe','icon-archive','backup','9',NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('57','Strony www','/sites','icon-website','website','9',NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('58','Rejestr transferów','/data-transfers','glyphicon glyphicon-transfer','data_transfers','10',NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('59','Rejestr pobrań','/data-transfers/pobrania','glyphicon glyphicon-transfer','data_transfers','10',NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('60','Rejestr udostępnienień','/data-transfers/udostepnienia','glyphicon glyphicon-transfer','data_transfers','10',NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('61','Rejestr powierzeń','/data-transfers/powierzenia','glyphicon glyphicon-transfer','data_transfers','10',NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('62','Podmioty','/companies','fa fa-building-o','companies','10',NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('63','Pracownicy','/company-employees','fa fa-building-o','companies','10',NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('64','Lista przepływów','/flows','glyphicon glyphicon-transfer','data_transfers','10',NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('65','Wydarzenia','/flows-events','glyphicon glyphicon-transfer','companies','10',NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('66','Role w przepływach','/flows-roles','fa fa-users','companies','10',NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('67','Analiza ryzyka','/risk-assessment','glyphicon glyphicon-transfer','risk-assessment','11',NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('68','Atrybuty','/risk-assessment/index-attributes','glyphicon glyphicon-transfer','risk-assessment-attributes','11',NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('69','Podatności','/risk-assessment/index-susceptibilites','glyphicon glyphicon-transfer','risk-assessment-susceptibilites','11',NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('70','Aktywa','/risk-assessment/index-assets','glyphicon glyphicon-transfer','risk-assessment-assets','11',NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('71','Zagrożenia','/risk-assessment/index-risks','glyphicon glyphicon-transfer','risk-assessment-risks','11',NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('72','Zabezpieczenia','/risk-assessment/index-safeguards','glyphicon glyphicon-transfer','risk-assessment-risks','11',NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('73','Klasyfikacja','/risk-assessment/index-classifications','glyphicon glyphicon-transfer','risk-assessment-risks','11',NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('74','Dane rejestracji','/registration-data','glyphicon glyphicon-transfer','surveys','12',NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('75','Szkolenia','/courses','fa fa-graduation-cap','courses','13',NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('76','Testy','/exams','fa fa-graduation-cap','exams','13',NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('77','Kategorie szkoleń','/course-categories','fa fa-list-ol','courses','13',NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('78','Kategorie testów','/exam-categories','fa fa-list-ol','courses','13',NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('79','Ankiety','/surveys','glyphicon glyphicon-transfer','surveys','13',NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('80','Ankiety - zarządzanie','/surveys/manage','glyphicon glyphicon-th-list','risk-assessment-attributes','13',NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('81','Rejestr zdarzeń','/events','icon-shuffle-2','events','17',NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('82','Firmy','/eventscompanies','fa fa-building-o','events','17',NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('83','Rodzaje osób','/eventspersonstypes','icon-cc-by','events','17',NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('84','Osoby','/eventspersons','icon-users','events','17',NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('85','Pojazdy','/eventscars','icon-traffic-cone','events','17',NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('86','Podstawowe informacje','/config/company-information','icon-wrench','admin','19',NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('87','Logi z operacji','/config/logi','icon-wrench','admin','19',NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('88','Logi z logowań','/config/login-history','icon-key-inv','profile','19',NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('89','Zmiana hasła','/home/zmianahasla','icon-key-inv','profile','20',NULL);
insert into `menu` (`id`, `label`, `path`, `icon`, `rel`, `parent_id`, `activate-routes`) values('90','Połączone konta','/shared-users','icon-key-inv','profile','20',NULL);
