-- PHP MySQL Dump
--
-- Environnement: DEV
-- Generated: Fri, 03 Jun 2016 21:47:32 +0000
-- PHP Version: 5.4.12

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: homemenus
--

-- ------------------------------------------------------------

--
-- Table structure for table `carte`
--

CREATE TABLE `carte` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `id_categorie` int(11) NOT NULL,
  `is_visible` tinyint(1) DEFAULT '1',
  `ordre` int(11) NOT NULL,
  `commentaire` text,
  `limite_supplement` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_carte_categorie` (`id_categorie`),
  CONSTRAINT `fk_carte_categorie` FOREIGN KEY (`id_categorie`) REFERENCES `restaurant_categorie` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=367 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `carte`
--

INSERT INTO `carte` (`id`, `nom`, `id_categorie`, `is_visible`, `ordre`, `commentaire`, `limite_supplement`) VALUES
('1', 'Marguerita', '2', '1', '0', 'Base tomate, mozza, olives noire, origan', '0'),
('2', 'Côte de boeuf', '3', '1', '0', '400 g. Au sel de Guérande. Juste grillée, bien saignante, un délice', '0'),
('3', 'frites', '4', '0', '0', '', '0'),
('4', 'haricots vert', '4', '0', '0', '', '0'),
('5', 'produit simple', '5', '1', '0', '', '0'),
('6', 'produit avec option', '5', '1', '0', '', '0'),
('7', 'produit avec 1 accompagnement', '5', '1', '0', '', '0'),
('8', 'produit multi accompagement', '5', '1', '0', '', '0'),
('9', 'produit avec supplement', '5', '1', '0', '', '0'),
('10', 'Poulet tandoori', '6', '1', '0', 'filet de poulet marinée et cuit au tandoor', '0'),
('11', 'Poulet Tikka', '6', '1', '0', 'Filet de poulet mariné et cuit au tandoor', '0'),
('12', 'Poulet Garlic', '6', '1', '0', 'Filet de poulet à l''ail et cuit au tandoor', '0'),
('13', 'Chicken pakora', '6', '1', '0', 'Beignets de poulet, ail, gingembre et épices', '0'),
('14', 'Seekh Kabab', '6', '1', '0', 'Gigot d''agneau haché en brochette avec oignons et épices, grillé au tandoor', '0'),
('15', 'Gambas Tandoori', '6', '1', '0', 'Gambas décortiquées émincées en sauce grillées au tandoor', '0'),
('16', 'Fish Pakora', '6', '1', '0', 'Filet de colin et de lieu, ail, gingembre et épices', '0'),
('17', 'Poisson Tikka', '6', '1', '0', 'Poisson mariné et cuit au tandoor', '0'),
('18', 'Salade Maharaja', '6', '1', '0', 'Salade de concombre, tomate, maïs et fines herbes', '0'),
('19', 'Raita', '6', '1', '0', 'Salade de concombre au yaourt, tomates', '0'),
('20', 'Salade Tandoori', '6', '1', '0', 'Salade de poulet grillé, tomates, concombre et olives', '0'),
('21', 'Samossa Legumes', '6', '1', '0', 'Beignets fourrés de pomme de terre, petits pois, oignons, épices', '0'),
('22', 'Onion Pakora', '6', '1', '0', 'Beignets d''oignons, coriandre, épices', '0'),
('23', 'Pakora aubergines', '6', '1', '0', 'Beignets d''aubergines en tranches, coriandre, épices', '0'),
('24', 'Mix pakora', '6', '1', '0', 'Variété de beignets de légumes : aubergines, oignons, pomme de terre, choux-fleur', '0'),
('25', 'Soupe dalcha', '6', '1', '0', 'Soupe de lentilles et poulet avec diverses épices', '0'),
('26', 'Maharaja mix grill (pour 1 personne)', '7', '1', '0', 'Variété d''entrées tandoori : poulet tandoori, poulet tikka, agneau tikka, agneau seekh habab, gambas tandoori', '0'),
('27', 'Maharaja mix grill (pour 2 personnes)', '7', '1', '0', 'Variété d''entrées tandoori : poulet tandoori, poulet tikka, agneau tikka, agneau seekh habab, gambas tandoori', '0'),
('28', 'Maharaja mix beignets (pour 1 personne)', '7', '1', '0', 'Variétés de beignets : chicken pakora, oignons, aubergines, samossa légumes, fish pakora, pommes de terres, choux-fleur', '0'),
('29', 'Maharaja mix beignets (pour 2 personnes)', '7', '1', '0', 'Variétés de beignets : chicken pakora, oignons, aubergines, samossa légumes, fish pakora, pommes de terres, choux-fleur', '0'),
('30', 'Chapati', '8', '1', '0', 'Pain nature farine complète pâte non levée', '0'),
('31', 'Nan', '8', '1', '0', 'Pâte à pain levée à la farine blanche nature', '0'),
('32', 'Nan au fromage', '8', '1', '0', 'Pâte à pain levée fourrée au fromage', '0'),
('33', 'Nan au fromage et garlic', '8', '1', '0', 'Nan au fromage et à l''ail', '0'),
('34', 'Nan kulcha', '8', '1', '0', 'Nan fourée de pomme de terre, oignons, petits pois, épices', '0'),
('35', 'Nan pista', '8', '1', '0', 'Nan aux pistaches et amandes', '0'),
('36', 'Nan Keema', '8', '1', '0', 'Pain farine blanche fourée à la viande hachée, coriandre et épices', '0'),
('37', 'Nan Peshawari', '8', '1', '0', 'Nan fourré aux amandes, noix de coco, sucre, safran et beurre', '0'),
('38', 'Poulet curry', '9', '1', '0', 'Cuisse de poulet avec sauce au curry traditionnelle du nord de l''inde', '0'),
('39', 'Poulet madras', '9', '1', '0', 'Cuisse de poulet avec sauce au curry traditionnelle du Nord de l''Inde. Très relevé', '0'),
('40', 'Poulet Bhuna', '9', '1', '0', 'Poulet désossé avec tomates, gingembre, coriandre fraîche, moyennement épicé', '0'),
('41', 'Poulet saag', '9', '1', '0', 'Poulet désossé cuit avec épinards, tomates, coriandre fraîche. Moyennemen épicé', '0'),
('42', 'Poulet shahi korma', '9', '1', '0', 'Poulet désossé avec sauce aux amandes, pistaches et crème fraîche', '0'),
('43', 'Poulet kashmirie korma', '9', '1', '0', 'Poulet désossé avec sauce tomate, noix de coco, amandes, raisins secs et crème fraîche', '0'),
('44', 'Poulet multani', '9', '1', '0', 'Poulet désossé avec ail, oignons, poivrons verts. Moyennement épicé', '0'),
('45', 'Poulet makhani', '9', '1', '0', 'Filet de poulet cuit au tandoor avec sauce tomate, beurre, amandes et crème fraîche', '0'),
('46', 'Poulet margala', '9', '1', '0', 'Poulet désossé cuit dans une sauce yaourt avec épices et herbes indiennes', '0'),
('47', 'Poulet tikka massala', '9', '1', '0', 'Filet de poulet grillé au four tandoor, tomates, oignons, ail et coriandre', '0'),
('48', 'Poulet Maharaja', '9', '1', '0', 'Poulet désossé avec ail, oignons et lentilles indiennes', '0'),
('49', 'Poulet bengali', '9', '1', '0', 'Poulet désossé avec sauce tomate, purée de mangue, citron, sauce sucrée - salée', '0'),
('50', 'Poulet balti', '9', '1', '0', 'Poulet désossé avec sauce aux oignons, tomate, poivrons verts, ail et gingembre. Moyennement épicé.', '0'),
('51', 'Poulet kadahi', '9', '1', '0', 'Poulet désossé avec sauce tomate, oignons, gingembre, ail, coriandre. Moyennement épicé', '0'),
('52', 'Boeuf curry', '10', '1', '0', 'Boeuf aux fines herbes', '0'),
('53', 'Boeuf madras', '10', '1', '0', '(spécialité du sud de l''Inde) Boeuf aux épices', '0'),
('54', 'Boeuf vindaloo', '10', '1', '0', 'Boeuf aux pommes de terre et épices', '0'),
('55', 'Boeuf korma', '10', '1', '0', 'Boeuf aux noix de cajou, amandes, crème fraîche et raisin secs', '0'),
('56', 'Boeuf dopiaza', '10', '1', '0', 'Boeuf aux oignons et épices', '0'),
('57', 'Boeuf aubergines', '10', '0', '0', 'Boeuf, aubergines, épices', '0'),
('58', 'Boeuf palak', '10', '1', '0', 'Agréable mélange de boeuf, épinards', '0'),
('59', 'Agneau rogan josh', '11', '1', '0', 'Gigot d''agneau coupé en dés avec sauce curry. Spécialité du Nord de l''Inde', '0'),
('60', 'Agneau madras', '11', '1', '0', 'Gigot d''agneau coupé en dés avec sauce au curry traditionnelle du Sud de l''Inde.', '0'),
('61', 'Agneau vindaloo', '11', '1', '0', 'Gigot d''agneau coupé en dés avec sauce tomate, gingembre, pommes de terre. Relevé', '0'),
('62', 'Agneau saag', '11', '1', '0', 'Gigot d''agneau coupé en dés cuit avec épinards, tomates, coriandre fraîche', '0'),
('63', 'Agneau shahi korma', '11', '1', '0', 'Gigot d''agneau coupé en dés avec sauce aux amandes, pistaches et crème fraîche', '0'),
('64', 'Agneau do piaza', '11', '1', '0', 'Gigot d''agneau coupé en dés aux oignons dorés. Moyennement épicé', '0'),
('65', 'Agneau tikka massala', '11', '1', '0', 'Gigot d''agneau cuit au tandoor avec ail, tomates et coriandre', '0'),
('66', 'Agneau benghan', '11', '1', '0', 'Gigot d''agneau avec sauce aux oignons, tomates et aubergines', '0'),
('67', 'Agneau kadahi', '11', '1', '0', 'Gigot d''agneau avec sauce tomate, oignons verts, gingembre, ail, coriandre', '0'),
('68', 'Agneau balti', '11', '1', '0', 'Gigot d''agneau avec une sauce aux oignons, tomates, gimgembre, poivrons verts, coriandre', '0'),
('69', 'Biryani poulet', '12', '1', '0', 'Poulet désossé mijoté avec riz basmati aux épices variés. Peu épicé', '0'),
('70', 'Biryani agneau', '12', '1', '0', 'Agneau désossé mijoté avec riz basmati aux épices variés. Peu épicé', '0'),
('71', 'Biryani crevettes', '12', '1', '0', 'Crevettes décortiquées mijotées avec riz basmati aux épices variés. Peu épicé', '0'),
('72', 'Biryani maharaja', '12', '1', '0', 'Poulet, agneau et crevettes cuits avec riz basmati, safran, coriandre, noix de cajou, filet d''amandes', '0'),
('73', 'Biryani légumes', '12', '1', '0', 'Variété de légumes mijotés avec riz basmati aux épices variés.', '0'),
('74', 'poisson curry', '13', '1', '0', 'Poisson cuit au tandoor avec sauce au curry traditionnelle du Nord de l''Inde', '0'),
('75', 'Poisson mangla special', '13', '1', '0', 'Poisson cuit au tandoor avec sauce tomate, ail, gingembre, coriandre. Moyennement épicé', '0'),
('76', 'Crevette curry', '13', '1', '0', 'Crevette décortiquées avec sauce curry traditionnelle du Nord de l''Inde', '0'),
('77', 'Crevette bhuna', '13', '1', '0', 'Crevette décortiquées avec sauce tomate, gingembre, coriandre. Moyennement épicé', '0'),
('78', 'Crevette shahi', '13', '1', '0', 'Crevette décortiquées avec sauce aux amandes, pistaches et crème fraîche', '0'),
('79', 'Gambas special maharaja', '13', '1', '0', 'Gambas décortiquées grillées au tandoor avec sauce tomate, oignons, coriandre fraîche et épices', '0'),
('80', 'Aloo punjabi', '14', '1', '0', 'Pommes de terre avec sauce tomate, oignons, ail et épices', '0'),
('81', 'Aloo matar', '14', '1', '0', 'Pommes de terre et petits pois avec sauce tomate et épices', '0'),
('82', 'Aloo palak', '14', '1', '0', 'Pommes de terre cuite avec épinard, tomates, coriandre fraîche. Moyennement épicé', '0'),
('83', 'Aloo Gobhi', '14', '1', '0', 'Pommes de terre cuites avec choux-fleurs, tomates, gingembre et coriandre fraîche. Moyennement épicé', '0'),
('84', 'Palak paneer', '14', '1', '0', 'Epinards cuits avec tomates, oignons et fromage frais maison', '0'),
('85', 'Matar paneer', '14', '1', '0', 'Petits pois cuits avec tomates, oignons et fromage frais maison', '0'),
('86', 'Dal tadaka', '14', '1', '0', 'Lentilles indiennes cuites avec tomates fraîches, coriandre, ail. Moyennement épicé', '0'),
('87', 'Dal shahi', '14', '1', '0', 'Lentilles indiennes cuites avec tomates, pistaches, amandes, noix de cajou et crème fraîche', '0'),
('88', 'Benghan burtha', '14', '1', '0', 'Purée d''aubergines. Spécialité de notre chef. Relevé à la demande', '0'),
('89', 'Riz pillau', '15', '1', '0', 'Riz basmati safrané et cuit à la vapeur', '0'),
('90', 'Riz kashmirie', '15', '1', '0', 'Riz basmati cuit à la vapeur aux amandes, noix de cajou et raisins secs', '0'),
('91', 'Riz maharaja', '15', '1', '0', 'Riz rissolé dans du beurre avec petits pois, pois chiches, cardamone, cumin', '0'),
('92', 'Halwa', '16', '1', '0', 'Pâtisserie de semoule avec coco, pistache et amande', '0'),
('93', 'Kheer', '16', '1', '0', 'Gâteau de riz au lait amande et pistache', '0'),
('94', 'Lassi sucre', '16', '1', '0', 'Boisson à base de yaourt et lait', '0'),
('95', 'Lassi mangue ou banane', '16', '1', '0', 'Lassi mangue ou banane 50cl', '0'),
('96', 'Lassi maharaja', '16', '1', '0', 'Lassi mangue pistache', '0'),
('97', 'Irish coffe', '16', '1', '0', 'Cocktail à base de café, sucre, whisky et crème', '0'),
('98', 'Naan Peshwari', '16', '1', '0', 'Pain fourré aux amandes, noix de coco, sucre, safran et beurre', '0'),
('99', 'Vanille', '16', '0', '0', '', '0'),
('100', 'Passion', '16', '0', '0', '', '0'),
('101', 'Mangue', '16', '0', '0', '', '0'),
('102', 'Fraise', '16', '0', '0', '', '0'),
('103', 'Sorbet', '16', '0', '0', '(2 parfums au choix)', '0'),
('104', 'Côtes du Rhône aoc Réserve Noël Briday', '21', '1', '0', 'Plein et charnu, au nez de confiture de fraise et de caramel', '0'),
('105', 'Côtes de Provence aoc Desbastides, Cave de St-Tropez', '22', '1', '0', 'Assez pâle, frais et réglissé', '0'),
('106', 'Evian', '18', '1', '0', '', '0'),
('107', 'San pellegrino', '18', '1', '0', '', '0'),
('108', 'Coca-Cola', '19', '1', '0', '33cl', '0'),
('109', 'Coca-Cola light', '19', '1', '0', '33cl', '0'),
('110', 'Coca-Cola zero', '19', '1', '0', '33cl', '0'),
('111', 'Bordeaux-Supérieur aoc Chapelle de Barbe', '21', '1', '0', 'Au nezde fruits noirs, avec une petite note fraîche en finale', '0'),
('112', 'Saint-Emilion aoc Château Tour de Beauregard', '21', '1', '0', 'Plein et charnu, avec des tannins enrobés', '0'),
('113', 'Brouilly aoc Château de Corcelles', '21', '1', '0', 'Souple, gourmand et fruité, au nez de baies rouges', '0'),
('114', 'S1', '25', '1', '0', '1 Soupe, 1 Salade, 1 Riz, 18 California makis : 6 avocat saumon, 6 avocat surimi, 6 avocat thon', '0'),
('115', 'S2', '25', '1', '0', '1 Soupe, 1 Salade, 1 Riz, 18 Makis printemps : 6 avocat saumon, 6 avocat thon, 6 avocat crevette', '0'),
('116', 'S3', '25', '1', '0', '1 Soupe, 1 Salade, 1 Riz, 6 Makis printemps avocat saumon, 6 saumon roll, 6 soja roll avocat saumon', '0'),
('117', 'S4', '25', '1', '0', '1 Soupe, 1 Salade, 1 Riz, 8 futomakis ou uramaki', '0'),
('118', 'S5', '25', '1', '0', '1 Soupe, 1 Salade, 1 Riz, 6 Makis flocon, 6 Makis royal, 6 California avocat saumon', '0'),
('119', 'S6', '25', '1', '0', '1 Soupe, 1 Salade, 1 Riz, 9 Sashimis assortis, 6 Makis saumon, 4 Sushis', '0'),
('120', 'S7', '25', '1', '0', '1 Soupe, 1 Salade, 1 Riz, 9 Sashimis assortis, 6 Sushis', '0'),
('121', 'S8', '25', '1', '0', '1 Soupe, 1 Salade, 1 Riz, 10 Sushis assortis', '0'),
('122', 'S9', '25', '1', '0', '1  Soupe, 1 Salade, 1 Riz, 24 Sashimis assortis', '0'),
('123', 'S10', '25', '1', '0', '1 Soupe, 1 Salade, 1 Riz, 6 Makis saumon, 5 brochettes : 1 boulettes de poulet, 1 boeuf fromage, 1 boeuf, 1 aile de poulet, 1 poulet', '0'),
('124', 'S11', '25', '1', '0', '1 Soupe, 1 Salade, 1 Riz, 6 Sushis assortis, 5 brochettes : 1 boulettes de poulet, 1 boeuf au fromage, 1 boeuf, 1 aile de poulet, 1 poulet', '0'),
('125', 'S12', '25', '1', '0', '1 Soupe, 1 Salade, 1 Riz, 6 california avocat saumon, 2 Sushis saumon, 5 brochettes : 1 poulet, 1 boulette de poulet, 1 boeuf au fromage, 1 boeuf, 1 aile de poulet', '0'),
('126', 'S13', '25', '1', '0', '1 Soupe, 1 Salade, 1 Riz, 6 Makis royal, 5 brochettes : 1 boulettes de poulet, 1 boeuf au fromage, 1 boeuf, 1 aile de poulet, 1 poulet', '0'),
('127', 'S14', '25', '1', '0', '1 Soupe, 1 Salade, 1 Riz, 9 Sashimis assortis, 5 brochettes : 1 boulettes de poulet, 1 boeuf au fromage, 1 boeuf, 1 aile de poulet, 1 poulet', '0'),
('128', 'P1', '25', '1', '0', '1 Soupe, 1 Salade, 1 Riz, 8 California avocat saumon, 4 Sashimis saumon, 4 Sushis saumon', '0'),
('129', 'P2', '25', '1', '0', '1 Soupe, 1 Salade, 1 Riz', '0'),
('130', 'G1', '25', '1', '0', '1 Soupe, 1 Salade, 1 Riz, 5 Gyoza raviolis japonais 9 Sashimis assortis', '0'),
('131', 'B1', '25', '1', '0', '1 Soupe, 1 Salade, 1 Riz, 5 brochettes : 2 courgettes, 2 champignons de paris, 1 Tofu', '0'),
('132', 'B2', '25', '1', '0', '1 Soupe, 1 Salade, 1 Riz', '0'),
('133', 'G2', '25', '1', '0', '1 Soupe, 1 Salade, 1 Riz, 5 Gyoza raviolis japonais, 5 brochettes : 1 boulettes de poulet, 1 boeuf au fromage, 1 boeuf, 1 aile de poulet, 1 poulet', '0'),
('134', 'B3', '25', '1', '0', '1 Soupe, 1 Salade, 1 Riz, 7 Brochettes : 2 gambas, 1 boulettes de poulet, 1 boeuf au fromage, 1 poulet, 1 canard, 1 boeuf', '0'),
('135', 'B4', '25', '1', '0', '1 Soupe, 1 Salade, 1 Riz, 6 Brochettes : 2 gambas, 1 coquilles St-Jacques, 2 saumon, 1 thon', '0'),
('136', 'B5', '25', '1', '0', '1 Soupe, 1 Salade, 1 Riz, 7 brochettes : 1 poulet, 2 aile de poulet, 1 boulettes de poulet, 1 boeuf au fromage, 1 canard, 1 champignon', '0'),
('137', 'B6', '25', '1', '0', '1 Soupe, 1 Salade, 1 Riz, 8 brochettes : 1 gambas, 1 coquilles st-jacques, 2 saumon, 2 thon, 2 maquereau', '0'),
('138', 'B7', '25', '1', '0', '1 Soupe, 1 Salade, 1 Riz', '0'),
('139', 'B8', '25', '1', '0', '1 Soupe, 1 Salade, 1 Riz, 4 Brochettes de gambas', '0'),
('140', 'T1', '25', '1', '0', '1 Soupe, 1 Salade, 1 riz, Tempura crevette', '0'),
('141', 'T2', '25', '1', '0', '1 Soupe, 1 Salade, 1 riz, Tempura poulet pané', '0'),
('142', 'BT1 (pour 2 personnes)', '25', '1', '0', '2 soupes, 2 Salades, 2 Riz, 6 California avocat saumon, 8 Sushis assortis, 15 Sashimis assortis, 8 Brochettes variées', '0'),
('143', 'BT2 (pour 2 personnes)', '25', '1', '0', '2 Soupes, 2 Salades, 2 Riz, 6 Makis saumon, 6 California avocat saumon, 4 Futomakis, 10 Sushi assortis, 20 Sashimis assortis', '0'),
('144', 'BT4 (pour 4 personnes)', '25', '1', '0', '4 Soupes, 4 Salades, 4 Riz, 20 Sushi assortis, 20 Sashimi assortis, 6 Maki printemps, 6 Saumon rolls cheese, 6 Maki thon, 6 Calfornia saumon avocat', '0'),
('145', 'F1', '26', '0', '0', '8 Maki royal', '0'),
('146', 'F2', '26', '0', '0', '6 Maki california tempura crevettes', '0'),
('147', 'F3', '26', '0', '0', '6 Maki california saumon avocat', '0'),
('148', 'F4', '26', '0', '0', '6 Maki california thon cuit mayo', '0'),
('149', 'F5', '26', '0', '0', '6 Maki printemps saumon avocat', '0'),
('150', 'F6', '26', '0', '0', '6 Maki neige saumon', '0'),
('151', 'F7', '26', '0', '0', '6 Maki saumon, 2 Sushi saumon', '0'),
('152', 'F8', '26', '0', '0', 'Temaki saumon avocat', '0'),
('153', 'F11', '26', '0', '0', '6 Crousti cheese saumon', '0'),
('154', 'F11B', '26', '0', '0', '8 California masago', '0'),
('155', 'F17', '26', '0', '0', '4 Sashimi saumon', '0'),
('156', 'F12', '26', '0', '0', '4 Nêms poulet', '0'),
('157', 'F16', '26', '0', '0', '4 Raviolis japonais', '0'),
('158', 'F17B', '26', '0', '0', 'Crevette panée (4pcs)', '0'),
('159', 'F16B', '26', '0', '0', 'Beignet maki (4pcs)', '0'),
('160', 'F22', '26', '0', '0', '4 Brochettes : 4 boeuf fromage', '0'),
('161', 'F21', '26', '0', '0', '5 Brochettes : 2 poulet, 2 boulettes de poulet, 1 boeuf fromage', '0'),
('162', 'F23', '26', '0', '0', '4 Brochettes : 2 poulet, 2 boeuf fromage', '0'),
('163', 'F24', '26', '0', '0', '4 Brochettes : 2 saumon, 2 boeuf fromage', '0'),
('164', 'F14', '26', '0', '0', '4 Sushi saumon', '0'),
('165', 'F14B', '26', '0', '0', '4 Sushi assortis', '0'),
('166', 'C1', '27', '1', '0', 'Chirashi saumon', '0'),
('167', 'C2', '27', '1', '0', 'Chirashi thon', '0'),
('168', 'C3', '27', '1', '0', 'Chirashi assortiment', '0'),
('169', 'C4', '27', '1', '0', 'Chirashi anguille', '0'),
('170', 'Soupe miso (601)', '28', '1', '0', '', '0'),
('171', 'Salade japonaise (602)', '28', '1', '0', '', '0'),
('172', 'Salade saumon et avocat (603)', '28', '1', '0', '', '0'),
('173', 'Riz sauté saumon (604)', '28', '1', '0', '', '0'),
('174', 'Raviolis japonais (605)', '28', '1', '0', '6 pcs', '0'),
('175', 'Nêms poulet ou crevettes (605A)', '28', '1', '0', '6 pcs', '0'),
('176', 'Udon sautées poulet et crevettes (606)', '28', '1', '0', '', '0'),
('177', 'Riz nature (607)', '28', '1', '0', '', '0'),
('178', 'Riz vinaigré (608)', '28', '1', '0', '', '0'),
('179', 'Nouilles sautées au nature (609)', '28', '1', '0', '', '0'),
('180', 'Salade d''algues (610)', '28', '1', '0', '', '0'),
('181', '2 fromage pannée (610B)', '28', '1', '0', '', '0'),
('182', 'Beignet maki saumon fromage (611)', '28', '1', '0', '6 pcs', '0'),
('183', 'Beignet poisson (612)', '28', '1', '0', '6 pcs', '0'),
('184', 'Beignet calamars (613)', '28', '1', '0', '6 pcs', '0'),
('185', 'Thon (101)', '29', '1', '0', '', '0'),
('186', 'Saumon (102)', '29', '1', '0', '', '0'),
('187', 'Daurade (103)', '29', '1', '0', '', '0'),
('188', 'Crevette (104)', '29', '1', '0', '', '0'),
('189', 'Maquereau (105)', '29', '1', '0', '', '0'),
('190', 'Saumon mi-cuit (106)', '29', '1', '0', '', '0'),
('191', 'Oeuf de saumon (107)', '29', '1', '0', '', '0'),
('192', 'Poulpe (108)', '29', '1', '0', '', '0'),
('193', 'Anguille (109)', '29', '1', '0', '', '0'),
('194', 'Saumon avocat (110)', '29', '1', '0', '', '0'),
('195', 'Saumon fromage (111)', '29', '1', '0', '', '0'),
('196', 'Salade', '33', '0', '0', '', '0'),
('197', 'Royale', '31', '1', '0', 'Tomate, emmental, mozzarella, jambon blanc, champignons', '0'),
('198', 'Don Lorenzo', '31', '1', '0', 'Tomate, emmental, mozzarella, jambon blanc, champignons', '0'),
('199', 'Don théo', '31', '1', '0', 'Tomate, emmental, mozzarella, anchois, câpres, olives', '0'),
('200', 'Don pétrus', '31', '1', '0', 'Tomate, emmental, mozzarelle, aubergines, poivrons, basilic', '0'),
('201', 'Mielleuse aux 3 fromages', '31', '1', '0', 'Tomate, emmental, mozzarella, chèvre, miel', '0'),
('202', 'Don Hugo', '31', '1', '0', 'Tomate, Emmental, mozzarella, chorizo, poivrons piquillos, basilic', '0'),
('203', 'Calzone (soufflée)', '31', '1', '0', 'Chausson, tomate, emmental, mozzarelle, j''aune d''oeuf, jambon blanc, champignons', '0'),
('204', 'L''Italienne', '32', '1', '0', 'Blanche, emmental, mozzarella, jambon italien, basilic', '0'),
('205', 'Don Toine', '32', '1', '0', 'Blanche, emmental, mozzarella, tomate confites, jambon de cochon de lait', '0'),
('206', 'Donna Juliana', '32', '1', '0', 'Tomate, emmental, mozzarella, aubergines, poivrons, basilic', '0'),
('207', 'Bardot', '32', '1', '0', 'blanche, emmental, mozzarella, champignons, jambon, miel', '0'),
('208', 'Sucrine', '32', '1', '0', 'Blanche, roquefort, emmental, mozzarella, confit d''oignons, poires', '0'),
('209', 'Charcuteries et Tapas provençaux', '34', '1', '0', '', '0'),
('210', 'Ravioles au fromage crème basilic', '35', '1', '0', 'parmesan', '0'),
('211', 'Pennes petits pois', '35', '1', '0', 'crème tomatée, champignons parmesan', '0'),
('212', 'Pennes à la tomate provençale', '35', '1', '0', 'parmesan', '0'),
('213', 'Pennes crème au basilic', '35', '1', '0', 'parmesan', '0'),
('214', 'Pennes sautées au chorizo', '35', '1', '0', '', '0'),
('215', 'Coca-Cola', '36', '1', '0', '', '0'),
('216', 'Coca-Cola zero', '36', '1', '0', '', '0'),
('217', 'Rosé de provence', '37', '1', '0', '75 cl', '0'),
('218', 'Chardonnay Val de loire', '37', '1', '0', 'blanc, 75 cl', '0'),
('219', 'Coca-Cola', '38', '1', '0', '33 cl', '0'),
('220', 'Coca-Cola zero', '38', '1', '0', '33 cl', '0'),
('221', 'Nastro azzurto', '38', '1', '0', '33 cl', '0'),
('222', 'Bière 1664', '38', '1', '0', '33 cl', '0'),
('223', 'Jus de fruits', '38', '1', '0', '20 cl', '0'),
('224', 'Vittel', '38', '1', '0', '50 cl', '0'),
('225', 'Badoit', '38', '1', '0', '50 cl', '0'),
('226', 'Perrier', '38', '1', '0', '33 cl', '0'),
('227', 'Farnese', '39', '1', '0', 'Montepulciano (abruzzes)', '0'),
('228', 'Lambrusco sec', '39', '1', '0', 'Terre Verdiane (emilia romagna)', '0'),
('229', 'Ciro', '39', '1', '0', 'classico (calabre)', '0'),
('230', 'Barbera d''Alba', '39', '1', '0', 'Raimonda (Piemont)', '0'),
('231', 'Nero d''Avola', '39', '1', '0', 'Mandrarossa (sicilia)', '0'),
('232', 'Passemiento', '39', '1', '0', 'Anteca Agricultura', '0'),
('233', 'Farnese Edizione', '39', '1', '0', 'Autoctoni (toscane)', '0'),
('234', 'Côte de Provence', '41', '1', '0', 'Alycaste Porquerolles', '0'),
('235', 'Minuty', '41', '1', '0', 'Prestige', '0'),
('236', 'Chiaretto classico', '42', '1', '0', '', '0'),
('237', 'Rosé de Venise', '42', '1', '0', 'Delle Venezie', '0'),
('238', 'Chablis', '43', '1', '0', 'Finage', '0'),
('239', 'Chardonnay', '43', '1', '0', 'Sicile', '0'),
('240', 'Salade tomates Mozzarella au basilic', '44', '1', '0', 'Tomate, mozzarella, basilic', '0'),
('241', 'Mortadella aux pistaches', '44', '1', '0', '', '0'),
('242', 'Antipasti de légumes grillés', '44', '1', '0', 'Poivrons, aubergines, oignons confits, artichauts à la romaine, tomates, mozzarella', '0'),
('243', 'Chiffonnade de charcuteries italiennes', '44', '1', '0', 'San Daniele, coppa, mortadella, pancetta, saucisson', '0'),
('244', 'Chiffonnade de jambon San Daniele', '44', '1', '0', '', '0'),
('245', 'La plancha', '44', '1', '0', 'Terrine, rillette d''oie, jambon italien, coppa, saucisson', '0'),
('246', 'Salade verte', '44', '1', '0', 'Salade verte, vinaigrette, échalotes, persil', '0'),
('247', 'Salade Mixte', '44', '1', '0', 'Salade verte, tomates, échalotes et fines herbes', '0'),
('248', 'Assiette d''Italie', '45', '1', '0', 'Jambon San Daniele, coppa, antipasti de légumes, linguine au basilic', '0'),
('249', 'Assiette Italienne', '45', '1', '0', 'Jambon de parme, tomates, mozzarella, poivrons rouges grillés, assiette de frites ou salade verte', '0'),
('250', 'Linguine à la bolognaise', '46', '1', '0', '', '0'),
('251', 'Linguine à la crème et aux deux jambons', '46', '1', '0', '', '0'),
('252', 'Linguine à la carbonara', '46', '1', '0', '', '0'),
('253', 'Penne à la matriciana', '46', '1', '0', '', '0'),
('254', 'Penne à la putanesca', '46', '1', '0', '', '0'),
('255', 'Penne aux quatre fromages', '46', '1', '0', '', '0'),
('256', 'Penne aux deux saumons', '46', '1', '0', '', '0'),
('257', 'Lasagnes maison à la bolognaise', '46', '1', '0', '', '0'),
('258', 'Lasagnes maison à la sicilienne', '46', '1', '0', '', '0'),
('259', 'Ravioli aux 3 viandes', '46', '1', '0', 'Sauce aux cèpes', '0'),
('260', 'Ravioli à la ricotta et épinards', '46', '1', '0', 'Sauce à la crème, noix de muscade et parmesan', '0'),
('261', 'Osso Bucco de veau à la milanaise', '47', '1', '0', 'et sa granarola', '0'),
('262', 'Escalope à la bolognaise', '47', '1', '0', 'Escalope de veau panée gratinée au fromage, jambon de parme et sauce bolognaise', '0'),
('263', 'Picatta au marsala et amandes', '47', '1', '0', 'Petites escalopes de veau sauce marsala et amandes', '0'),
('264', 'Piccata de dinde au citron', '47', '1', '0', 'Petites escalopes de dinde, sauce crème et citron', '0'),
('265', 'Margherita', '48', '1', '0', 'Sauce tomate, mozzarella fraîche, huile d''olive et basilic', '0'),
('266', 'Napoletana', '48', '1', '0', 'Sauce tomate, mozzarella fraîche, anchois, câpres, olives', '0'),
('267', 'Della casa', '48', '1', '0', 'Sauce tomate, mozzarella, jambon, chorizo, oeuf, gruyère', '0'),
('268', 'Régina', '48', '1', '0', 'Sauce tomate, mozzarella, jambon, champignons, gruyère', '0'),
('269', 'Soufflé Vésuvio', '48', '1', '0', 'Sauce tomate, mozzarella, jambon, oeuf, gruyère', '0'),
('270', '4 saisons', '48', '1', '0', 'Sauce tomate, mozzarella, champignons, oignons, artichauts, poivrons, oeuf, gruyère', '0'),
('271', 'Orientale', '48', '1', '0', 'Sauce tomate, mozzarella, poivrons, oignons, merguez, oeuf, gruyère', '0'),
('272', 'Paysanne', '48', '1', '0', 'Sauce tomate, mozzarella, lardons, oeuf, gruyère', '0'),
('273', 'Bolognaise', '48', '1', '0', 'Sauce tomate, mozzarella, steak haché, persillade, oeuf, gruyère', '0'),
('274', 'Chèvre et noix', '48', '1', '0', 'Sauce tomate, mozzarella, chèvre, gruyère, miel, noix', '0'),
('275', '5 fomage', '48', '1', '0', 'Sauce tomate, mozzarella, gorgonzola, ricotta, parmesan, gruyère', '0'),
('276', 'Végétarienne', '48', '1', '0', 'Sauce tomate, mozzarella fraîche, roquette, tomate cerise, copeaux de parmesan, huile d''olive, vinaigre balsamique', '0'),
('277', 'Della Nonna', '48', '1', '0', 'Sauce tomate, mozzarella fraîche, basilic, jambon de parme', '0'),
('278', 'Du Sud', '48', '1', '0', 'Sauce tomate, mozzarella, aubergines et poivrons grillés, basilic', '0'),
('279', 'Mousse au chocolat', '50', '1', '0', '', '0'),
('280', 'Fondant au chocolat', '50', '1', '0', '', '0'),
('281', 'Panna cotta à la pistache', '50', '1', '0', '', '0'),
('282', 'Charlotte au chocolat', '50', '1', '0', '', '0'),
('283', 'Tiramisu', '50', '1', '0', '', '0'),
('284', 'Thon (201)', '51', '1', '0', '', '0'),
('285', 'Saumon (202)', '51', '1', '0', '', '0'),
('286', 'Daurade (203)', '51', '1', '0', '', '0'),
('287', 'Maquereau (204)', '51', '1', '0', '', '0'),
('288', 'Poulpe (205)', '51', '1', '0', '', '0'),
('289', 'Assortiment (206)', '51', '1', '0', '10 pcs', '0'),
('290', 'Sashimi mi-cuit (207)', '51', '1', '0', '10 pcs', '0'),
('291', 'thon (301)', '52', '1', '0', '', '0'),
('292', 'saumon (302)', '52', '1', '0', '', '0'),
('293', 'concombre (303)', '52', '1', '0', '', '0'),
('294', 'radis marines (304)', '52', '1', '0', '', '0'),
('295', 'avocat (305)', '52', '1', '0', '', '0'),
('296', 'saumon fromage (306)', '52', '1', '0', '', '0'),
('297', 'anguille (307)', '52', '1', '0', '', '0'),
('298', 'Maki royal (308)', '52', '1', '0', '', '0'),
('299', 'Témaki (309)', '52', '1', '0', '1 pièce', '0'),
('300', '310', '52', '1', '0', '8 pièces', '0'),
('301', 'Maki neige saumon avocat (311)', '52', '1', '0', '', '0'),
('302', 'Maki neige saumon (311A)', '52', '1', '0', '', '0'),
('303', 'Maki neige Thon cuit (311B)', '52', '1', '0', '', '0'),
('304', 'Maki neige saumon fromage (311C)', '52', '1', '0', '', '0'),
('305', 'Saumon avocat (312-1)', '53', '1', '0', '', '0'),
('306', 'Thon avocat (312-2)', '53', '1', '0', '', '0'),
('307', 'Avocat cheese (312-3)', '53', '1', '0', '', '0'),
('308', 'Saumon spicy (312-5)', '53', '1', '0', '', '0'),
('309', 'Surimi spicy (312-5)', '53', '1', '0', '', '0'),
('310', 'Thon spicy (312-6)', '53', '1', '0', '', '0'),
('311', 'Avocat spicy (312-7)', '53', '1', '0', '', '0'),
('312', 'Saumon avocat (313-1)', '54', '1', '0', '', '0'),
('313', 'Thon avocat (313-2)', '54', '1', '0', '', '0'),
('314', 'Avocat cheese (313-3)', '54', '1', '0', '', '0'),
('315', 'Saumon cheese (313-4)', '54', '1', '0', '', '0'),
('316', 'Thon spicy (313-5)', '54', '1', '0', '', '0'),
('317', 'Saumon spicy (313-6)', '54', '1', '0', '', '0'),
('318', 'Avocat spicy (313-7)', '54', '1', '0', '', '0'),
('319', 'Avocat saumon (314-1)', '55', '1', '0', '', '0'),
('320', 'Surimi avocat (314-2)', '55', '1', '0', '', '0'),
('321', 'Avocat spicy (314-3)', '55', '1', '0', '', '0'),
('322', 'Saumon spicy (314-4)', '55', '1', '0', '', '0'),
('323', 'Tempura crevettes (315)', '55', '1', '0', '', '0'),
('324', 'Saumon (cheese) rolls (316)', '55', '1', '0', '', '0'),
('325', 'Saumon (cheese) concombre rolls (317)', '55', '1', '0', '', '0'),
('326', 'masago saumon avocat (318)', '55', '1', '0', '', '0'),
('327', 'Crousti cheese saumon (319)', '55', '1', '0', '', '0'),
('328', 'Oignon frits saumon (319B)', '55', '1', '0', '', '0'),
('329', 'Thon (501)', '56', '1', '0', '', '0'),
('330', 'Saumon (502)', '56', '1', '0', '', '0'),
('331', 'Maquereau (503)', '56', '1', '0', '', '0'),
('332', 'Champignon (504)', '56', '1', '0', '', '0'),
('333', 'Poulet (505)', '56', '1', '0', '', '0'),
('334', 'Boulettes de poulet (506)', '56', '1', '0', '', '0'),
('335', 'Canard (507)', '56', '1', '0', '', '0'),
('336', 'Boeuf (508)', '56', '1', '0', '', '0'),
('337', 'Courgette (509)', '56', '1', '0', '', '0'),
('338', 'Ailes de poulet (510)', '56', '1', '0', '', '0'),
('339', 'Gambas (511)', '56', '1', '0', '', '0'),
('340', 'Boeuf au fromage (512)', '56', '1', '0', '', '0'),
('341', 'Coquilles St-Jacques', '56', '1', '0', '', '0'),
('342', 'Nougat chinois', '57', '1', '0', '', '0'),
('343', 'Litchi', '57', '1', '0', '', '0'),
('344', 'Gingembre confit', '57', '1', '0', '', '0'),
('345', 'Dorayaki au haricot', '57', '1', '0', '', '0'),
('346', 'Maki chocolat', '57', '1', '0', '', '0'),
('347', 'Daifuku', '57', '1', '0', '', '0'),
('348', 'Yokan pâte de haricot rouge', '57', '1', '0', '', '0'),
('349', 'Coca-Cola', '58', '1', '0', '33cl', '0'),
('350', 'Coca-Cola light', '58', '1', '0', '33cl', '0'),
('351', 'Coca-Cola zero', '58', '1', '0', '33cl', '0'),
('352', 'Orangina', '58', '1', '0', '33cl', '0'),
('353', 'Jus de fruits', '58', '1', '0', '', '0'),
('354', 'Bière Asahi', '58', '1', '0', '33cl', '0'),
('355', 'Bière Kirin', '58', '1', '0', '33cl', '0'),
('356', 'Bière Tsing Tao', '58', '1', '0', '33cl', '0'),
('357', 'Bière Heineken', '58', '1', '0', '', '0'),
('358', 'Touraine sauvignon', '58', '1', '0', '', '0'),
('359', 'Côte de Provence', '58', '1', '0', '', '0'),
('360', 'Côtes de Rhône', '58', '1', '0', '', '0'),
('361', 'Muscadet', '58', '1', '0', '', '0'),
('362', 'Bordeaux', '58', '1', '0', '', '0'),
('363', 'Poêlée de petites seiches et calamar', '35', '1', '0', 'ail et chorizo', '0'),
('364', 'Saumur champigny', '37', '1', '0', 'rouge, 75cl', '0'),
('365', 'Panacotta coulis de fruits rouge', '59', '1', '0', '', '0'),
('366', 'Tiramisu spéculos caramel', '59', '1', '0', '', '0');

-- ------------------------------------------------------------

--
-- Table structure for table `carte_accompagnement`
--

CREATE TABLE `carte_accompagnement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_carte` int(11) NOT NULL,
  `limite` int(11) NOT NULL DEFAULT '0',
  `id_categorie` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_carte_accompagnement_carte` (`id_carte`),
  KEY `fk_carte_accompagnement_categorie` (`id_categorie`),
  CONSTRAINT `fk_carte_accompagnement_carte` FOREIGN KEY (`id_carte`) REFERENCES `carte` (`id`),
  CONSTRAINT `fk_carte_accompagnement_categorie` FOREIGN KEY (`id_categorie`) REFERENCES `restaurant_categorie` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `carte_accompagnement`
--

INSERT INTO `carte_accompagnement` (`id`, `id_carte`, `limite`, `id_categorie`) VALUES
('1', '2', '1', '4'),
('2', '7', '1', '5'),
('3', '8', '1', '2'),
('4', '8', '1', '5');

-- ------------------------------------------------------------

--
-- Table structure for table `carte_accompagnement_contenu`
--

CREATE TABLE `carte_accompagnement_contenu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_carte_accompagnement` int(11) NOT NULL,
  `id_accompagnement` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_cac_ca` (`id_carte_accompagnement`),
  KEY `fk_carte_accompagnement_contenu_accompagnement` (`id_accompagnement`),
  CONSTRAINT `fk_cac_ca` FOREIGN KEY (`id_carte_accompagnement`) REFERENCES `carte_accompagnement` (`id`),
  CONSTRAINT `fk_carte_accompagnement_contenu_accompagnement` FOREIGN KEY (`id_accompagnement`) REFERENCES `carte` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `carte_accompagnement_contenu`
--

INSERT INTO `carte_accompagnement_contenu` (`id`, `id_carte_accompagnement`, `id_accompagnement`) VALUES
('1', '1', '3'),
('2', '1', '4'),
('3', '2', '5'),
('4', '3', '1'),
('5', '4', '5'),
('6', '4', '6');

-- ------------------------------------------------------------

--
-- Table structure for table `carte_disponibilite`
--

CREATE TABLE `carte_disponibilite` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_carte` int(11) NOT NULL,
  `id_horaire` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_carte_disponibilite_carte` (`id_carte`),
  KEY `fk_carte_disponibilite_horaire` (`id_horaire`),
  CONSTRAINT `fk_carte_disponibilite_carte` FOREIGN KEY (`id_carte`) REFERENCES `carte` (`id`),
  CONSTRAINT `fk_carte_disponibilite_horaire` FOREIGN KEY (`id_horaire`) REFERENCES `restaurant_horaires` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2883 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `carte_disponibilite`
--

INSERT INTO `carte_disponibilite` (`id`, `id_carte`, `id_horaire`) VALUES
('1', '1', '1'),
('2', '1', '2'),
('3', '1', '3'),
('4', '1', '4'),
('5', '1', '5'),
('6', '1', '6'),
('7', '2', '21'),
('8', '2', '22'),
('9', '2', '23'),
('10', '2', '24'),
('11', '2', '25'),
('12', '2', '26'),
('13', '2', '27'),
('14', '3', '21'),
('15', '3', '22'),
('16', '3', '23'),
('17', '3', '24'),
('18', '3', '25'),
('19', '3', '26'),
('20', '3', '27'),
('21', '4', '21'),
('22', '4', '22'),
('23', '4', '23'),
('24', '4', '24'),
('25', '4', '25'),
('26', '4', '26'),
('27', '4', '27'),
('28', '2', '21'),
('29', '2', '22'),
('30', '2', '23'),
('31', '2', '24'),
('32', '2', '25'),
('33', '2', '26'),
('34', '2', '27'),
('35', '5', '1'),
('36', '5', '2'),
('37', '5', '3'),
('38', '5', '4'),
('39', '5', '5'),
('40', '5', '6'),
('41', '6', '1'),
('42', '6', '2'),
('43', '6', '3'),
('44', '6', '4'),
('45', '6', '5'),
('46', '6', '6'),
('47', '7', '1'),
('48', '7', '2'),
('49', '7', '3'),
('50', '7', '4'),
('51', '7', '5'),
('52', '7', '6'),
('53', '8', '1'),
('54', '8', '2'),
('55', '8', '3'),
('56', '8', '4'),
('57', '8', '5'),
('58', '8', '6'),
('59', '9', '1'),
('60', '9', '2'),
('61', '9', '3'),
('62', '9', '4'),
('63', '9', '5'),
('64', '9', '6'),
('65', '10', '28'),
('66', '10', '29'),
('67', '10', '30'),
('68', '10', '31'),
('69', '10', '32'),
('70', '10', '33'),
('71', '10', '34'),
('72', '11', '28'),
('73', '11', '29'),
('74', '11', '30'),
('75', '11', '31'),
('76', '11', '32'),
('77', '11', '33'),
('78', '11', '34'),
('79', '10', '28'),
('80', '10', '29'),
('81', '10', '30'),
('82', '10', '31'),
('83', '10', '32'),
('84', '10', '33'),
('85', '10', '34'),
('86', '11', '28'),
('87', '11', '29'),
('88', '11', '30'),
('89', '11', '31'),
('90', '11', '32'),
('91', '11', '33'),
('92', '11', '34'),
('93', '12', '28'),
('94', '12', '29'),
('95', '12', '30'),
('96', '12', '31'),
('97', '12', '32'),
('98', '12', '33'),
('99', '12', '34'),
('100', '13', '28'),
('101', '13', '29'),
('102', '13', '30'),
('103', '13', '31'),
('104', '13', '32'),
('105', '13', '33'),
('106', '13', '34'),
('107', '14', '28'),
('108', '14', '29'),
('109', '14', '30'),
('110', '14', '31'),
('111', '14', '32'),
('112', '14', '33'),
('113', '14', '34'),
('114', '15', '28'),
('115', '15', '29'),
('116', '15', '30'),
('117', '15', '31'),
('118', '15', '32'),
('119', '15', '33'),
('120', '15', '34'),
('121', '16', '28'),
('122', '16', '29'),
('123', '16', '30'),
('124', '16', '31'),
('125', '16', '32'),
('126', '16', '33'),
('127', '16', '34'),
('128', '17', '28'),
('129', '17', '29'),
('130', '17', '30'),
('131', '17', '31'),
('132', '17', '32'),
('133', '17', '33'),
('134', '17', '34'),
('135', '18', '28'),
('136', '18', '29'),
('137', '18', '30'),
('138', '18', '31'),
('139', '18', '32'),
('140', '18', '33'),
('141', '18', '34'),
('142', '19', '28'),
('143', '19', '29'),
('144', '19', '30'),
('145', '19', '31'),
('146', '19', '32'),
('147', '19', '33'),
('148', '19', '34'),
('149', '20', '28'),
('150', '20', '29'),
('151', '20', '30'),
('152', '20', '31'),
('153', '20', '32'),
('154', '20', '33'),
('155', '20', '34'),
('156', '21', '28'),
('157', '21', '29'),
('158', '21', '30'),
('159', '21', '31'),
('160', '21', '32'),
('161', '21', '33'),
('162', '21', '34'),
('163', '22', '28'),
('164', '22', '29'),
('165', '22', '30'),
('166', '22', '31'),
('167', '22', '32'),
('168', '22', '33'),
('169', '22', '34'),
('170', '23', '28'),
('171', '23', '29'),
('172', '23', '30'),
('173', '23', '31'),
('174', '23', '32'),
('175', '23', '33'),
('176', '23', '34'),
('177', '24', '28'),
('178', '24', '29'),
('179', '24', '30'),
('180', '24', '31'),
('181', '24', '32'),
('182', '24', '33'),
('183', '24', '34'),
('184', '25', '28'),
('185', '25', '29'),
('186', '25', '30'),
('187', '25', '31'),
('188', '25', '32'),
('189', '25', '33'),
('190', '25', '34'),
('191', '26', '28'),
('192', '26', '29'),
('193', '26', '30'),
('194', '26', '31'),
('195', '26', '32'),
('196', '26', '33'),
('197', '26', '34'),
('198', '27', '28'),
('199', '27', '29'),
('200', '27', '30'),
('201', '27', '31'),
('202', '27', '32'),
('203', '27', '33'),
('204', '27', '34'),
('205', '28', '28'),
('206', '28', '29'),
('207', '28', '30'),
('208', '28', '31'),
('209', '28', '32'),
('210', '28', '33'),
('211', '28', '34'),
('212', '29', '28'),
('213', '29', '29'),
('214', '29', '30'),
('215', '29', '31'),
('216', '29', '32'),
('217', '29', '33'),
('218', '29', '34'),
('219', '30', '28'),
('220', '30', '29'),
('221', '30', '30'),
('222', '30', '31'),
('223', '30', '32'),
('224', '30', '33'),
('225', '30', '34'),
('226', '31', '28'),
('227', '31', '29'),
('228', '31', '30'),
('229', '31', '31'),
('230', '31', '32'),
('231', '31', '33'),
('232', '31', '34'),
('233', '32', '28'),
('234', '32', '29'),
('235', '32', '30'),
('236', '32', '31'),
('237', '32', '32'),
('238', '32', '33'),
('239', '32', '34'),
('240', '33', '28'),
('241', '33', '29'),
('242', '33', '30'),
('243', '33', '31'),
('244', '33', '32'),
('245', '33', '33'),
('246', '33', '34'),
('247', '33', '28'),
('248', '33', '29'),
('249', '33', '30'),
('250', '33', '31'),
('251', '33', '32'),
('252', '33', '33'),
('253', '33', '34'),
('254', '34', '28'),
('255', '34', '29'),
('256', '34', '30'),
('257', '34', '31'),
('258', '34', '32'),
('259', '34', '33'),
('260', '34', '34'),
('261', '35', '28'),
('262', '35', '29'),
('263', '35', '30'),
('264', '35', '31'),
('265', '35', '32'),
('266', '35', '33'),
('267', '35', '34'),
('268', '36', '28'),
('269', '36', '29'),
('270', '36', '30'),
('271', '36', '31'),
('272', '36', '32'),
('273', '36', '33'),
('274', '36', '34'),
('275', '37', '28'),
('276', '37', '29'),
('277', '37', '30'),
('278', '37', '31'),
('279', '37', '32'),
('280', '37', '33'),
('281', '37', '34'),
('282', '38', '28'),
('283', '38', '29'),
('284', '38', '30'),
('285', '38', '31'),
('286', '38', '32'),
('287', '38', '33'),
('288', '38', '34'),
('289', '39', '28'),
('290', '39', '29'),
('291', '39', '30'),
('292', '39', '31'),
('293', '39', '32'),
('294', '39', '33'),
('295', '39', '34'),
('296', '40', '28'),
('297', '40', '29'),
('298', '40', '30'),
('299', '40', '31'),
('300', '40', '32'),
('301', '40', '33'),
('302', '40', '34'),
('303', '41', '28'),
('304', '41', '29'),
('305', '41', '30'),
('306', '41', '31'),
('307', '41', '32'),
('308', '41', '33'),
('309', '41', '34'),
('310', '42', '28'),
('311', '42', '29'),
('312', '42', '30'),
('313', '42', '31'),
('314', '42', '32'),
('315', '42', '33'),
('316', '42', '34'),
('317', '43', '28'),
('318', '43', '29'),
('319', '43', '30'),
('320', '43', '31'),
('321', '43', '32'),
('322', '43', '33'),
('323', '43', '34'),
('324', '44', '28'),
('325', '44', '29'),
('326', '44', '30'),
('327', '44', '31'),
('328', '44', '32'),
('329', '44', '33'),
('330', '44', '34'),
('331', '45', '28'),
('332', '45', '29'),
('333', '45', '30'),
('334', '45', '31'),
('335', '45', '32'),
('336', '45', '33'),
('337', '45', '34'),
('338', '46', '28'),
('339', '46', '29'),
('340', '46', '30'),
('341', '46', '31'),
('342', '46', '32'),
('343', '46', '33'),
('344', '46', '34'),
('345', '47', '28'),
('346', '47', '29'),
('347', '47', '30'),
('348', '47', '31'),
('349', '47', '32'),
('350', '47', '33'),
('351', '47', '34'),
('352', '48', '28'),
('353', '48', '29'),
('354', '48', '30'),
('355', '48', '31'),
('356', '48', '32'),
('357', '48', '33'),
('358', '48', '34'),
('359', '49', '28'),
('360', '49', '29'),
('361', '49', '30'),
('362', '49', '31'),
('363', '49', '32'),
('364', '49', '33'),
('365', '49', '34'),
('366', '50', '28'),
('367', '50', '29'),
('368', '50', '30'),
('369', '50', '31'),
('370', '50', '32'),
('371', '50', '33'),
('372', '50', '34'),
('373', '51', '28'),
('374', '51', '29'),
('375', '51', '30'),
('376', '51', '31'),
('377', '51', '32'),
('378', '51', '33'),
('379', '51', '34'),
('380', '52', '28'),
('381', '52', '29'),
('382', '52', '30'),
('383', '52', '31'),
('384', '52', '32'),
('385', '52', '33'),
('386', '52', '34'),
('387', '53', '28'),
('388', '53', '29'),
('389', '53', '30'),
('390', '53', '31'),
('391', '53', '32'),
('392', '53', '33'),
('393', '53', '34'),
('394', '54', '28'),
('395', '54', '29'),
('396', '54', '30'),
('397', '54', '31'),
('398', '54', '32'),
('399', '54', '33'),
('400', '54', '34'),
('401', '55', '28'),
('402', '55', '29'),
('403', '55', '30'),
('404', '55', '31'),
('405', '55', '32'),
('406', '55', '33'),
('407', '55', '34'),
('408', '56', '28'),
('409', '56', '29'),
('410', '56', '30'),
('411', '56', '31'),
('412', '56', '32'),
('413', '56', '33'),
('414', '56', '34'),
('415', '57', '28'),
('416', '57', '29'),
('417', '57', '30'),
('418', '57', '31'),
('419', '57', '32'),
('420', '57', '33'),
('421', '57', '34'),
('422', '58', '28'),
('423', '58', '29'),
('424', '58', '30'),
('425', '58', '31'),
('426', '58', '32'),
('427', '58', '33'),
('428', '58', '34'),
('429', '59', '28'),
('430', '59', '29'),
('431', '59', '30'),
('432', '59', '31'),
('433', '59', '32'),
('434', '59', '33'),
('435', '59', '34'),
('436', '60', '28'),
('437', '60', '29'),
('438', '60', '30'),
('439', '60', '31'),
('440', '60', '32'),
('441', '60', '33'),
('442', '60', '34'),
('443', '61', '28'),
('444', '61', '29'),
('445', '61', '30'),
('446', '61', '31'),
('447', '61', '32'),
('448', '61', '33'),
('449', '61', '34'),
('450', '62', '28'),
('451', '62', '29'),
('452', '62', '30'),
('453', '62', '31'),
('454', '62', '32'),
('455', '62', '33'),
('456', '62', '34'),
('457', '63', '28'),
('458', '63', '29'),
('459', '63', '30'),
('460', '63', '31'),
('461', '63', '32'),
('462', '63', '33'),
('463', '63', '34'),
('464', '64', '28'),
('465', '64', '29'),
('466', '64', '30'),
('467', '64', '31'),
('468', '64', '32'),
('469', '64', '33'),
('470', '64', '34'),
('471', '65', '28'),
('472', '65', '29'),
('473', '65', '30'),
('474', '65', '31'),
('475', '65', '32'),
('476', '65', '33'),
('477', '65', '34'),
('478', '66', '28'),
('479', '66', '29'),
('480', '66', '30'),
('481', '66', '31'),
('482', '66', '32'),
('483', '66', '33'),
('484', '66', '34'),
('485', '67', '28'),
('486', '67', '29'),
('487', '67', '30'),
('488', '67', '31'),
('489', '67', '32'),
('490', '67', '33'),
('491', '67', '34'),
('492', '68', '28'),
('493', '68', '29'),
('494', '68', '30'),
('495', '68', '31'),
('496', '68', '32'),
('497', '68', '33'),
('498', '68', '34'),
('499', '69', '28'),
('500', '69', '29'),
('501', '69', '30'),
('502', '69', '31'),
('503', '69', '32'),
('504', '69', '33'),
('505', '69', '34'),
('506', '70', '28'),
('507', '70', '29'),
('508', '70', '30'),
('509', '70', '31'),
('510', '70', '32'),
('511', '70', '33'),
('512', '70', '34'),
('513', '71', '28'),
('514', '71', '29'),
('515', '71', '30'),
('516', '71', '31'),
('517', '71', '32'),
('518', '71', '33'),
('519', '71', '34'),
('520', '72', '28'),
('521', '72', '29'),
('522', '72', '30'),
('523', '72', '31'),
('524', '72', '32'),
('525', '72', '33'),
('526', '72', '34'),
('527', '73', '28'),
('528', '73', '29'),
('529', '73', '30'),
('530', '73', '31'),
('531', '73', '32'),
('532', '73', '33'),
('533', '73', '34'),
('534', '74', '28'),
('535', '74', '29'),
('536', '74', '30'),
('537', '74', '31'),
('538', '74', '32'),
('539', '74', '33'),
('540', '74', '34'),
('541', '75', '28'),
('542', '75', '29'),
('543', '75', '30'),
('544', '75', '31'),
('545', '75', '32'),
('546', '75', '33'),
('547', '75', '34'),
('548', '76', '28'),
('549', '76', '29'),
('550', '76', '30'),
('551', '76', '31'),
('552', '76', '32'),
('553', '76', '33'),
('554', '76', '34'),
('555', '77', '28'),
('556', '77', '29'),
('557', '77', '30'),
('558', '77', '31'),
('559', '77', '32'),
('560', '77', '33'),
('561', '77', '34'),
('562', '78', '28'),
('563', '78', '29'),
('564', '78', '30'),
('565', '78', '31'),
('566', '78', '32'),
('567', '78', '33'),
('568', '78', '34'),
('569', '79', '28'),
('570', '79', '29'),
('571', '79', '30'),
('572', '79', '31'),
('573', '79', '32'),
('574', '79', '33'),
('575', '79', '34'),
('576', '80', '28'),
('577', '80', '29'),
('578', '80', '30'),
('579', '80', '31'),
('580', '80', '32'),
('581', '80', '33'),
('582', '80', '34'),
('583', '81', '28'),
('584', '81', '29'),
('585', '81', '30'),
('586', '81', '31'),
('587', '81', '32'),
('588', '81', '33'),
('589', '81', '34'),
('590', '82', '28'),
('591', '82', '29'),
('592', '82', '30'),
('593', '82', '31'),
('594', '82', '32'),
('595', '82', '33'),
('596', '82', '34'),
('597', '83', '28'),
('598', '83', '29'),
('599', '83', '30'),
('600', '83', '31'),
('601', '83', '32'),
('602', '83', '33'),
('603', '83', '34'),
('604', '84', '28'),
('605', '84', '29'),
('606', '84', '30'),
('607', '84', '31'),
('608', '84', '32'),
('609', '84', '33'),
('610', '84', '34'),
('611', '85', '28'),
('612', '85', '29'),
('613', '85', '30'),
('614', '85', '31'),
('615', '85', '32'),
('616', '85', '33'),
('617', '85', '34'),
('618', '86', '28'),
('619', '86', '29'),
('620', '86', '30'),
('621', '86', '31'),
('622', '86', '32'),
('623', '86', '33'),
('624', '86', '34'),
('625', '87', '28'),
('626', '87', '29'),
('627', '87', '30'),
('628', '87', '31'),
('629', '87', '32'),
('630', '87', '33'),
('631', '87', '34'),
('632', '88', '28'),
('633', '88', '29'),
('634', '88', '30'),
('635', '88', '31'),
('636', '88', '32'),
('637', '88', '33'),
('638', '88', '34'),
('639', '89', '28'),
('640', '89', '29'),
('641', '89', '30'),
('642', '89', '31'),
('643', '89', '32'),
('644', '89', '33'),
('645', '89', '34'),
('646', '90', '28'),
('647', '90', '29'),
('648', '90', '30'),
('649', '90', '31'),
('650', '90', '32'),
('651', '90', '33'),
('652', '90', '34'),
('653', '91', '28'),
('654', '91', '29'),
('655', '91', '30'),
('656', '91', '31'),
('657', '91', '32'),
('658', '91', '33'),
('659', '91', '34'),
('660', '92', '28'),
('661', '92', '29'),
('662', '92', '30'),
('663', '92', '31'),
('664', '92', '32'),
('665', '92', '33'),
('666', '92', '34'),
('667', '93', '28'),
('668', '93', '29'),
('669', '93', '30'),
('670', '93', '31'),
('671', '93', '32'),
('672', '93', '33'),
('673', '93', '34'),
('674', '94', '28'),
('675', '94', '29'),
('676', '94', '30'),
('677', '94', '31'),
('678', '94', '32'),
('679', '94', '33'),
('680', '94', '34'),
('681', '95', '28'),
('682', '95', '29'),
('683', '95', '30'),
('684', '95', '31'),
('685', '95', '32'),
('686', '95', '33'),
('687', '95', '34'),
('688', '96', '28'),
('689', '96', '29'),
('690', '96', '30'),
('691', '96', '31'),
('692', '96', '32'),
('693', '96', '33'),
('694', '96', '34'),
('695', '97', '28'),
('696', '97', '29'),
('697', '97', '30'),
('698', '97', '31'),
('699', '97', '32'),
('700', '97', '33'),
('701', '97', '34'),
('702', '98', '28'),
('703', '98', '29'),
('704', '98', '30'),
('705', '98', '31'),
('706', '98', '32'),
('707', '98', '33'),
('708', '98', '34'),
('709', '99', '28'),
('710', '99', '29'),
('711', '99', '30'),
('712', '99', '31'),
('713', '99', '32'),
('714', '99', '33'),
('715', '99', '34'),
('716', '100', '28'),
('717', '100', '29'),
('718', '100', '30'),
('719', '100', '31'),
('720', '100', '32'),
('721', '100', '33'),
('722', '100', '34'),
('723', '101', '28'),
('724', '101', '29'),
('725', '101', '30'),
('726', '101', '31'),
('727', '101', '32'),
('728', '101', '33'),
('729', '101', '34'),
('730', '102', '28'),
('731', '102', '29'),
('732', '102', '30'),
('733', '102', '31'),
('734', '102', '32'),
('735', '102', '33'),
('736', '102', '34'),
('737', '103', '28'),
('738', '103', '29'),
('739', '103', '30'),
('740', '103', '31'),
('741', '103', '32'),
('742', '103', '33'),
('743', '103', '34'),
('744', '104', '28'),
('745', '104', '29'),
('746', '104', '30'),
('747', '104', '31'),
('748', '104', '32'),
('749', '104', '33'),
('750', '104', '34'),
('751', '105', '28'),
('752', '105', '29'),
('753', '105', '30'),
('754', '105', '31'),
('755', '105', '32'),
('756', '105', '33'),
('757', '105', '34'),
('758', '106', '28'),
('759', '106', '29'),
('760', '106', '30'),
('761', '106', '31'),
('762', '106', '32'),
('763', '106', '33'),
('764', '106', '34'),
('765', '107', '28'),
('766', '107', '29'),
('767', '107', '30'),
('768', '107', '31'),
('769', '107', '32'),
('770', '107', '33'),
('771', '107', '34'),
('772', '108', '28'),
('773', '108', '29'),
('774', '108', '30'),
('775', '108', '31'),
('776', '108', '32'),
('777', '108', '33'),
('778', '108', '34'),
('779', '109', '28'),
('780', '109', '29'),
('781', '109', '30'),
('782', '109', '31'),
('783', '109', '32'),
('784', '109', '33'),
('785', '109', '34'),
('786', '110', '28'),
('787', '110', '29'),
('788', '110', '30'),
('789', '110', '31'),
('790', '110', '32'),
('791', '110', '33'),
('792', '110', '34'),
('793', '111', '28'),
('794', '111', '29'),
('795', '111', '30'),
('796', '111', '31'),
('797', '111', '32'),
('798', '111', '33'),
('799', '111', '34'),
('800', '112', '28'),
('801', '112', '29'),
('802', '112', '30'),
('803', '112', '31'),
('804', '112', '32'),
('805', '112', '33'),
('806', '112', '34'),
('807', '113', '28'),
('808', '113', '29'),
('809', '113', '30'),
('810', '113', '31'),
('811', '113', '32'),
('812', '113', '33'),
('813', '113', '34'),
('814', '114', '35'),
('815', '114', '36'),
('816', '114', '37'),
('817', '114', '38'),
('818', '114', '39'),
('819', '114', '40'),
('820', '114', '41'),
('821', '115', '35'),
('822', '115', '36'),
('823', '115', '37'),
('824', '115', '38'),
('825', '115', '39'),
('826', '115', '40'),
('827', '115', '41'),
('828', '116', '35'),
('829', '116', '36'),
('830', '116', '37'),
('831', '116', '38'),
('832', '116', '39'),
('833', '116', '40'),
('834', '116', '41'),
('835', '117', '35'),
('836', '117', '36'),
('837', '117', '37'),
('838', '117', '38'),
('839', '117', '39'),
('840', '117', '40'),
('841', '117', '41'),
('842', '118', '35'),
('843', '118', '36'),
('844', '118', '37'),
('845', '118', '38'),
('846', '118', '39'),
('847', '118', '40'),
('848', '118', '41'),
('849', '117', '35'),
('850', '117', '36'),
('851', '117', '37'),
('852', '117', '38'),
('853', '117', '39'),
('854', '117', '40'),
('855', '117', '41'),
('856', '119', '35'),
('857', '119', '36'),
('858', '119', '37'),
('859', '119', '38'),
('860', '119', '39'),
('861', '119', '40'),
('862', '119', '41'),
('863', '120', '35'),
('864', '120', '36'),
('865', '120', '37'),
('866', '120', '38'),
('867', '120', '39'),
('868', '120', '40'),
('869', '120', '41'),
('870', '121', '35'),
('871', '121', '36'),
('872', '121', '37'),
('873', '121', '38'),
('874', '121', '39'),
('875', '121', '40'),
('876', '121', '41'),
('877', '122', '35'),
('878', '122', '36'),
('879', '122', '37'),
('880', '122', '38'),
('881', '122', '39'),
('882', '122', '40'),
('883', '122', '41'),
('884', '123', '35'),
('885', '123', '36'),
('886', '123', '37'),
('887', '123', '38'),
('888', '123', '39'),
('889', '123', '40'),
('890', '123', '41'),
('891', '124', '35'),
('892', '124', '36'),
('893', '124', '37'),
('894', '124', '38'),
('895', '124', '39'),
('896', '124', '40'),
('897', '124', '41'),
('898', '125', '35'),
('899', '125', '36'),
('900', '125', '37'),
('901', '125', '38'),
('902', '125', '39'),
('903', '125', '40'),
('904', '125', '41'),
('905', '126', '35'),
('906', '126', '36'),
('907', '126', '37'),
('908', '126', '38'),
('909', '126', '39'),
('910', '126', '40'),
('911', '126', '41'),
('912', '127', '35'),
('913', '127', '36'),
('914', '127', '37'),
('915', '127', '38'),
('916', '127', '39'),
('917', '127', '40'),
('918', '127', '41'),
('919', '128', '35'),
('920', '128', '36'),
('921', '128', '37'),
('922', '128', '38'),
('923', '128', '39'),
('924', '128', '40'),
('925', '128', '41'),
('926', '129', '35'),
('927', '129', '36'),
('928', '129', '37'),
('929', '129', '38'),
('930', '129', '39'),
('931', '129', '40'),
('932', '129', '41'),
('933', '130', '35'),
('934', '130', '36'),
('935', '130', '37'),
('936', '130', '38'),
('937', '130', '39'),
('938', '130', '40'),
('939', '130', '41'),
('940', '131', '35'),
('941', '131', '36'),
('942', '131', '37'),
('943', '131', '38'),
('944', '131', '39'),
('945', '131', '40'),
('946', '131', '41'),
('947', '132', '35'),
('948', '132', '36'),
('949', '132', '37'),
('950', '132', '38'),
('951', '132', '39'),
('952', '132', '40'),
('953', '132', '41'),
('954', '133', '35'),
('955', '133', '36'),
('956', '133', '37'),
('957', '133', '38'),
('958', '133', '39'),
('959', '133', '40'),
('960', '133', '41'),
('961', '134', '35'),
('962', '134', '36'),
('963', '134', '37'),
('964', '134', '38'),
('965', '134', '39'),
('966', '134', '40'),
('967', '134', '41'),
('968', '135', '35'),
('969', '135', '36'),
('970', '135', '37'),
('971', '135', '38'),
('972', '135', '39'),
('973', '135', '40'),
('974', '135', '41'),
('975', '136', '35'),
('976', '136', '36'),
('977', '136', '37'),
('978', '136', '38'),
('979', '136', '39'),
('980', '136', '40'),
('981', '136', '41'),
('982', '137', '35'),
('983', '137', '36'),
('984', '137', '37'),
('985', '137', '38'),
('986', '137', '39'),
('987', '137', '40'),
('988', '137', '41'),
('989', '138', '35'),
('990', '138', '36'),
('991', '138', '37'),
('992', '138', '38'),
('993', '138', '39'),
('994', '138', '40'),
('995', '138', '41'),
('996', '139', '35'),
('997', '139', '36'),
('998', '139', '37'),
('999', '139', '38'),
('1000', '139', '39'),
('1001', '139', '40'),
('1002', '139', '41'),
('1003', '140', '35'),
('1004', '140', '36'),
('1005', '140', '37'),
('1006', '140', '38'),
('1007', '140', '39'),
('1008', '140', '40'),
('1009', '140', '41'),
('1010', '141', '35'),
('1011', '141', '36'),
('1012', '141', '37'),
('1013', '141', '38'),
('1014', '141', '39'),
('1015', '141', '40'),
('1016', '141', '41'),
('1017', '142', '35'),
('1018', '142', '36'),
('1019', '142', '37'),
('1020', '142', '38'),
('1021', '142', '39'),
('1022', '142', '40'),
('1023', '142', '41'),
('1024', '143', '35'),
('1025', '143', '36'),
('1026', '143', '37'),
('1027', '143', '38'),
('1028', '143', '39'),
('1029', '143', '40'),
('1030', '143', '41'),
('1031', '144', '35'),
('1032', '144', '36'),
('1033', '144', '37'),
('1034', '144', '38'),
('1035', '144', '39'),
('1036', '144', '40'),
('1037', '144', '41'),
('1038', '144', '35'),
('1039', '144', '36'),
('1040', '144', '37'),
('1041', '144', '38'),
('1042', '144', '39'),
('1043', '144', '40'),
('1044', '144', '41'),
('1045', '145', '35'),
('1046', '145', '36'),
('1047', '145', '37'),
('1048', '145', '38'),
('1049', '145', '39'),
('1050', '145', '40'),
('1051', '145', '41'),
('1052', '146', '35'),
('1053', '146', '36'),
('1054', '146', '37'),
('1055', '146', '38'),
('1056', '146', '39'),
('1057', '146', '40'),
('1058', '146', '41'),
('1059', '147', '35'),
('1060', '147', '36'),
('1061', '147', '37'),
('1062', '147', '38'),
('1063', '147', '39'),
('1064', '147', '40'),
('1065', '147', '41'),
('1066', '148', '35'),
('1067', '148', '36'),
('1068', '148', '37'),
('1069', '148', '38'),
('1070', '148', '39'),
('1071', '148', '40'),
('1072', '148', '41'),
('1073', '149', '35'),
('1074', '149', '36'),
('1075', '149', '37'),
('1076', '149', '38'),
('1077', '149', '39'),
('1078', '149', '40'),
('1079', '149', '41'),
('1080', '150', '35'),
('1081', '150', '36'),
('1082', '150', '37'),
('1083', '150', '38'),
('1084', '150', '39'),
('1085', '150', '40'),
('1086', '150', '41'),
('1087', '151', '35'),
('1088', '151', '36'),
('1089', '151', '37'),
('1090', '151', '38'),
('1091', '151', '39'),
('1092', '151', '40'),
('1093', '151', '41'),
('1094', '152', '35'),
('1095', '152', '36'),
('1096', '152', '37'),
('1097', '152', '38'),
('1098', '152', '39'),
('1099', '152', '40'),
('1100', '152', '41'),
('1101', '153', '35'),
('1102', '153', '36'),
('1103', '153', '37'),
('1104', '153', '38'),
('1105', '153', '39'),
('1106', '153', '40'),
('1107', '153', '41'),
('1108', '154', '35'),
('1109', '154', '36'),
('1110', '154', '37'),
('1111', '154', '38'),
('1112', '154', '39'),
('1113', '154', '40'),
('1114', '154', '41'),
('1115', '155', '35'),
('1116', '155', '36'),
('1117', '155', '37'),
('1118', '155', '38'),
('1119', '155', '39'),
('1120', '155', '40'),
('1121', '155', '41'),
('1122', '156', '35'),
('1123', '156', '36'),
('1124', '156', '37'),
('1125', '156', '38'),
('1126', '156', '39'),
('1127', '156', '40'),
('1128', '156', '41'),
('1129', '157', '35'),
('1130', '157', '36'),
('1131', '157', '37'),
('1132', '157', '38'),
('1133', '157', '39'),
('1134', '157', '40'),
('1135', '157', '41'),
('1136', '158', '35'),
('1137', '158', '36'),
('1138', '158', '37'),
('1139', '158', '38'),
('1140', '158', '39'),
('1141', '158', '40'),
('1142', '158', '41'),
('1143', '159', '35'),
('1144', '159', '36'),
('1145', '159', '37'),
('1146', '159', '38'),
('1147', '159', '39'),
('1148', '159', '40'),
('1149', '159', '41'),
('1150', '160', '35'),
('1151', '160', '36'),
('1152', '160', '37'),
('1153', '160', '38'),
('1154', '160', '39'),
('1155', '160', '40'),
('1156', '160', '41'),
('1157', '161', '35'),
('1158', '161', '36'),
('1159', '161', '37'),
('1160', '161', '38'),
('1161', '161', '39'),
('1162', '161', '40'),
('1163', '161', '41'),
('1164', '162', '35'),
('1165', '162', '36'),
('1166', '162', '37'),
('1167', '162', '38'),
('1168', '162', '39'),
('1169', '162', '40'),
('1170', '162', '41'),
('1171', '163', '35'),
('1172', '163', '36'),
('1173', '163', '37'),
('1174', '163', '38'),
('1175', '163', '39'),
('1176', '163', '40'),
('1177', '163', '41'),
('1178', '164', '35'),
('1179', '164', '36'),
('1180', '164', '37'),
('1181', '164', '38'),
('1182', '164', '39'),
('1183', '164', '40'),
('1184', '164', '41'),
('1185', '165', '35'),
('1186', '165', '36'),
('1187', '165', '37'),
('1188', '165', '38'),
('1189', '165', '39'),
('1190', '165', '40'),
('1191', '165', '41'),
('1192', '166', '35'),
('1193', '166', '36'),
('1194', '166', '37'),
('1195', '166', '38'),
('1196', '166', '39'),
('1197', '166', '40'),
('1198', '166', '41'),
('1199', '167', '35'),
('1200', '167', '36'),
('1201', '167', '37'),
('1202', '167', '38'),
('1203', '167', '39'),
('1204', '167', '40'),
('1205', '167', '41'),
('1206', '168', '35'),
('1207', '168', '36'),
('1208', '168', '37'),
('1209', '168', '38'),
('1210', '168', '39'),
('1211', '168', '40'),
('1212', '168', '41'),
('1213', '169', '35'),
('1214', '169', '36'),
('1215', '169', '37'),
('1216', '169', '38'),
('1217', '169', '39'),
('1218', '169', '40'),
('1219', '169', '41'),
('1220', '170', '35'),
('1221', '170', '36'),
('1222', '170', '37'),
('1223', '170', '38'),
('1224', '170', '39'),
('1225', '170', '40'),
('1226', '170', '41'),
('1227', '171', '35'),
('1228', '171', '36'),
('1229', '171', '37'),
('1230', '171', '38'),
('1231', '171', '39'),
('1232', '171', '40'),
('1233', '171', '41'),
('1234', '172', '35'),
('1235', '172', '36'),
('1236', '172', '37'),
('1237', '172', '38'),
('1238', '172', '39'),
('1239', '172', '40'),
('1240', '172', '41'),
('1241', '173', '35'),
('1242', '173', '36'),
('1243', '173', '37'),
('1244', '173', '38'),
('1245', '173', '39'),
('1246', '173', '40'),
('1247', '173', '41'),
('1248', '174', '35'),
('1249', '174', '36'),
('1250', '174', '37'),
('1251', '174', '38'),
('1252', '174', '39'),
('1253', '174', '40'),
('1254', '174', '41'),
('1255', '175', '35'),
('1256', '175', '36'),
('1257', '175', '37'),
('1258', '175', '38'),
('1259', '175', '39'),
('1260', '175', '40'),
('1261', '175', '41'),
('1262', '176', '35'),
('1263', '176', '36'),
('1264', '176', '37'),
('1265', '176', '38'),
('1266', '176', '39'),
('1267', '176', '40'),
('1268', '176', '41'),
('1269', '175', '35'),
('1270', '175', '36'),
('1271', '175', '37'),
('1272', '175', '38'),
('1273', '175', '39'),
('1274', '175', '40'),
('1275', '175', '41'),
('1276', '177', '35'),
('1277', '177', '36'),
('1278', '177', '37'),
('1279', '177', '38'),
('1280', '177', '39'),
('1281', '177', '40'),
('1282', '177', '41'),
('1283', '178', '35'),
('1284', '178', '36'),
('1285', '178', '37'),
('1286', '178', '38'),
('1287', '178', '39'),
('1288', '178', '40'),
('1289', '178', '41'),
('1290', '179', '35'),
('1291', '179', '36'),
('1292', '179', '37'),
('1293', '179', '38'),
('1294', '179', '39'),
('1295', '179', '40'),
('1296', '179', '41'),
('1297', '180', '35'),
('1298', '180', '36'),
('1299', '180', '37'),
('1300', '180', '38'),
('1301', '180', '39'),
('1302', '180', '40'),
('1303', '180', '41'),
('1304', '180', '35'),
('1305', '180', '36'),
('1306', '180', '37'),
('1307', '180', '38'),
('1308', '180', '39'),
('1309', '180', '40'),
('1310', '180', '41'),
('1311', '181', '35'),
('1312', '181', '36'),
('1313', '181', '37'),
('1314', '181', '38'),
('1315', '181', '39'),
('1316', '181', '40'),
('1317', '181', '41'),
('1318', '182', '35'),
('1319', '182', '36'),
('1320', '182', '37'),
('1321', '182', '38'),
('1322', '182', '39'),
('1323', '182', '40'),
('1324', '182', '41'),
('1325', '183', '35'),
('1326', '183', '36'),
('1327', '183', '37'),
('1328', '183', '38'),
('1329', '183', '39'),
('1330', '183', '40'),
('1331', '183', '41'),
('1332', '184', '35'),
('1333', '184', '36'),
('1334', '184', '37'),
('1335', '184', '38'),
('1336', '184', '39'),
('1337', '184', '40'),
('1338', '184', '41'),
('1339', '185', '35'),
('1340', '185', '36'),
('1341', '185', '37'),
('1342', '185', '38'),
('1343', '185', '39'),
('1344', '185', '40'),
('1345', '185', '41'),
('1346', '186', '35'),
('1347', '186', '36'),
('1348', '186', '37'),
('1349', '186', '38'),
('1350', '186', '39'),
('1351', '186', '40'),
('1352', '186', '41'),
('1353', '187', '35'),
('1354', '187', '36'),
('1355', '187', '37'),
('1356', '187', '38'),
('1357', '187', '39'),
('1358', '187', '40'),
('1359', '187', '41'),
('1360', '188', '35'),
('1361', '188', '36'),
('1362', '188', '37'),
('1363', '188', '38'),
('1364', '188', '39'),
('1365', '188', '40'),
('1366', '188', '41'),
('1367', '188', '35'),
('1368', '188', '36'),
('1369', '188', '37'),
('1370', '188', '38'),
('1371', '188', '39'),
('1372', '188', '40'),
('1373', '188', '41'),
('1374', '189', '35'),
('1375', '189', '36'),
('1376', '189', '37'),
('1377', '189', '38'),
('1378', '189', '39'),
('1379', '189', '40'),
('1380', '189', '41'),
('1381', '190', '35'),
('1382', '190', '36'),
('1383', '190', '37'),
('1384', '190', '38'),
('1385', '190', '39'),
('1386', '190', '40'),
('1387', '190', '41'),
('1388', '191', '35'),
('1389', '191', '36'),
('1390', '191', '37'),
('1391', '191', '38'),
('1392', '191', '39'),
('1393', '191', '40'),
('1394', '191', '41'),
('1395', '192', '35'),
('1396', '192', '36'),
('1397', '192', '37'),
('1398', '192', '38'),
('1399', '192', '39'),
('1400', '192', '40'),
('1401', '192', '41'),
('1402', '193', '35'),
('1403', '193', '36'),
('1404', '193', '37'),
('1405', '193', '38'),
('1406', '193', '39'),
('1407', '193', '40'),
('1408', '193', '41'),
('1409', '194', '35'),
('1410', '194', '36'),
('1411', '194', '37'),
('1412', '194', '38'),
('1413', '194', '39'),
('1414', '194', '40'),
('1415', '194', '41'),
('1416', '195', '35'),
('1417', '195', '36'),
('1418', '195', '37'),
('1419', '195', '38'),
('1420', '195', '39'),
('1421', '195', '40'),
('1422', '195', '41'),
('1423', '196', '42'),
('1424', '196', '43'),
('1425', '196', '44'),
('1426', '196', '45'),
('1427', '196', '46'),
('1428', '197', '42'),
('1429', '197', '43'),
('1430', '197', '44'),
('1431', '197', '45'),
('1432', '197', '46'),
('1433', '196', '42'),
('1434', '196', '43'),
('1435', '196', '44'),
('1436', '196', '45'),
('1437', '196', '46'),
('1438', '198', '42'),
('1439', '198', '43'),
('1440', '198', '44'),
('1441', '198', '45'),
('1442', '198', '46'),
('1443', '199', '42'),
('1444', '199', '43'),
('1445', '199', '44'),
('1446', '199', '45'),
('1447', '199', '46'),
('1448', '197', '42'),
('1449', '197', '43'),
('1450', '197', '44'),
('1451', '197', '45'),
('1452', '197', '46'),
('1453', '198', '42'),
('1454', '198', '43'),
('1455', '198', '44'),
('1456', '198', '45'),
('1457', '198', '46'),
('1458', '199', '42'),
('1459', '199', '43'),
('1460', '199', '44'),
('1461', '199', '45'),
('1462', '199', '46'),
('1463', '200', '42'),
('1464', '200', '43'),
('1465', '200', '44'),
('1466', '200', '45'),
('1467', '200', '46'),
('1468', '201', '42'),
('1469', '201', '43'),
('1470', '201', '44'),
('1471', '201', '45'),
('1472', '201', '46'),
('1473', '202', '42'),
('1474', '202', '43'),
('1475', '202', '44'),
('1476', '202', '45'),
('1477', '202', '46'),
('1478', '203', '42'),
('1479', '203', '43'),
('1480', '203', '44'),
('1481', '203', '45'),
('1482', '203', '46'),
('1483', '204', '42'),
('1484', '204', '43'),
('1485', '204', '44'),
('1486', '204', '45'),
('1487', '204', '46'),
('1488', '205', '42'),
('1489', '205', '43'),
('1490', '205', '44'),
('1491', '205', '45'),
('1492', '205', '46'),
('1493', '206', '42'),
('1494', '206', '43'),
('1495', '206', '44'),
('1496', '206', '45'),
('1497', '206', '46'),
('1498', '207', '42'),
('1499', '207', '43'),
('1500', '207', '44'),
('1501', '207', '45'),
('1502', '207', '46'),
('1503', '208', '42'),
('1504', '208', '43'),
('1505', '208', '44'),
('1506', '208', '45'),
('1507', '208', '46'),
('1508', '209', '42'),
('1509', '209', '43'),
('1510', '209', '44'),
('1511', '209', '45'),
('1512', '209', '46'),
('1513', '210', '42'),
('1514', '210', '43'),
('1515', '210', '44'),
('1516', '210', '45'),
('1517', '210', '46'),
('1518', '211', '42'),
('1519', '211', '43'),
('1520', '211', '44'),
('1521', '211', '45'),
('1522', '211', '46'),
('1523', '212', '42'),
('1524', '212', '43'),
('1525', '212', '44'),
('1526', '212', '45'),
('1527', '212', '46'),
('1528', '213', '42'),
('1529', '213', '43'),
('1530', '213', '44'),
('1531', '213', '45'),
('1532', '213', '46'),
('1533', '214', '42'),
('1534', '214', '43'),
('1535', '214', '44'),
('1536', '214', '45'),
('1537', '214', '46'),
('1538', '215', '42'),
('1539', '215', '43'),
('1540', '215', '44'),
('1541', '215', '45'),
('1542', '215', '46'),
('1543', '216', '42'),
('1544', '216', '43'),
('1545', '216', '44'),
('1546', '216', '45'),
('1547', '216', '46'),
('1548', '217', '42'),
('1549', '217', '43'),
('1550', '217', '44'),
('1551', '217', '45'),
('1552', '217', '46'),
('1553', '218', '42'),
('1554', '218', '43'),
('1555', '218', '44'),
('1556', '218', '45'),
('1557', '218', '46'),
('1558', '219', '47'),
('1559', '219', '48'),
('1560', '219', '49'),
('1561', '219', '50'),
('1562', '219', '51'),
('1563', '219', '52'),
('1564', '219', '53'),
('1565', '220', '47'),
('1566', '220', '48'),
('1567', '220', '49'),
('1568', '220', '50'),
('1569', '220', '51'),
('1570', '220', '52'),
('1571', '220', '53'),
('1572', '221', '47'),
('1573', '221', '48'),
('1574', '221', '49'),
('1575', '221', '50'),
('1576', '221', '51'),
('1577', '221', '52'),
('1578', '221', '53'),
('1579', '222', '47'),
('1580', '222', '48'),
('1581', '222', '49'),
('1582', '222', '50'),
('1583', '222', '51'),
('1584', '222', '52'),
('1585', '222', '53'),
('1586', '223', '47'),
('1587', '223', '48'),
('1588', '223', '49'),
('1589', '223', '50'),
('1590', '223', '51'),
('1591', '223', '52'),
('1592', '223', '53'),
('1593', '223', '47'),
('1594', '223', '48'),
('1595', '223', '49'),
('1596', '223', '50'),
('1597', '223', '51'),
('1598', '223', '52'),
('1599', '223', '53'),
('1600', '224', '47'),
('1601', '224', '48'),
('1602', '224', '49'),
('1603', '224', '50'),
('1604', '224', '51'),
('1605', '224', '52'),
('1606', '224', '53'),
('1607', '225', '47'),
('1608', '225', '48'),
('1609', '225', '49'),
('1610', '225', '50'),
('1611', '225', '51'),
('1612', '225', '52'),
('1613', '225', '53'),
('1614', '226', '47'),
('1615', '226', '48'),
('1616', '226', '49'),
('1617', '226', '50'),
('1618', '226', '51'),
('1619', '226', '52'),
('1620', '226', '53'),
('1621', '227', '47'),
('1622', '227', '48'),
('1623', '227', '49'),
('1624', '227', '50'),
('1625', '227', '51'),
('1626', '227', '52'),
('1627', '227', '53'),
('1628', '228', '47'),
('1629', '228', '48'),
('1630', '228', '49'),
('1631', '228', '50'),
('1632', '228', '51'),
('1633', '228', '52'),
('1634', '228', '53'),
('1635', '229', '47'),
('1636', '229', '48'),
('1637', '229', '49'),
('1638', '229', '50'),
('1639', '229', '51'),
('1640', '229', '52'),
('1641', '229', '53'),
('1642', '230', '47'),
('1643', '230', '48'),
('1644', '230', '49'),
('1645', '230', '50'),
('1646', '230', '51'),
('1647', '230', '52'),
('1648', '230', '53'),
('1649', '231', '47'),
('1650', '231', '48'),
('1651', '231', '49'),
('1652', '231', '50'),
('1653', '231', '51'),
('1654', '231', '52'),
('1655', '231', '53'),
('1656', '232', '47'),
('1657', '232', '48'),
('1658', '232', '49'),
('1659', '232', '50'),
('1660', '232', '51'),
('1661', '232', '52'),
('1662', '232', '53'),
('1663', '233', '47'),
('1664', '233', '48'),
('1665', '233', '49'),
('1666', '233', '50'),
('1667', '233', '51'),
('1668', '233', '52'),
('1669', '233', '53'),
('1670', '234', '47'),
('1671', '234', '48'),
('1672', '234', '49'),
('1673', '234', '50'),
('1674', '234', '51'),
('1675', '234', '52'),
('1676', '234', '53'),
('1677', '235', '47'),
('1678', '235', '48'),
('1679', '235', '49'),
('1680', '235', '50'),
('1681', '235', '51'),
('1682', '235', '52'),
('1683', '235', '53'),
('1684', '236', '47'),
('1685', '236', '48'),
('1686', '236', '49'),
('1687', '236', '50'),
('1688', '236', '51'),
('1689', '236', '52'),
('1690', '236', '53'),
('1691', '237', '47'),
('1692', '237', '48'),
('1693', '237', '49'),
('1694', '237', '50'),
('1695', '237', '51'),
('1696', '237', '52'),
('1697', '237', '53'),
('1698', '238', '47'),
('1699', '238', '48'),
('1700', '238', '49'),
('1701', '238', '50'),
('1702', '238', '51'),
('1703', '238', '52'),
('1704', '238', '53'),
('1705', '239', '47'),
('1706', '239', '48'),
('1707', '239', '49'),
('1708', '239', '50'),
('1709', '239', '51'),
('1710', '239', '52'),
('1711', '239', '53'),
('1712', '240', '47'),
('1713', '240', '48'),
('1714', '240', '49'),
('1715', '240', '50'),
('1716', '240', '51'),
('1717', '240', '52'),
('1718', '240', '53'),
('1719', '241', '47'),
('1720', '241', '48'),
('1721', '241', '49'),
('1722', '241', '50'),
('1723', '241', '51'),
('1724', '241', '52'),
('1725', '241', '53'),
('1726', '242', '47'),
('1727', '242', '48'),
('1728', '242', '49'),
('1729', '242', '50'),
('1730', '242', '51'),
('1731', '242', '52'),
('1732', '242', '53'),
('1733', '243', '47'),
('1734', '243', '48'),
('1735', '243', '49'),
('1736', '243', '50'),
('1737', '243', '51'),
('1738', '243', '52'),
('1739', '243', '53'),
('1740', '244', '47'),
('1741', '244', '48'),
('1742', '244', '49'),
('1743', '244', '50'),
('1744', '244', '51'),
('1745', '244', '52'),
('1746', '244', '53'),
('1747', '245', '47'),
('1748', '245', '48'),
('1749', '245', '49'),
('1750', '245', '50'),
('1751', '245', '51'),
('1752', '245', '52'),
('1753', '245', '53'),
('1754', '246', '47'),
('1755', '246', '48'),
('1756', '246', '49'),
('1757', '246', '50'),
('1758', '246', '51'),
('1759', '246', '52'),
('1760', '246', '53'),
('1761', '247', '47'),
('1762', '247', '48'),
('1763', '247', '49'),
('1764', '247', '50'),
('1765', '247', '51'),
('1766', '247', '52'),
('1767', '247', '53'),
('1768', '248', '47'),
('1769', '248', '48'),
('1770', '248', '49'),
('1771', '248', '50'),
('1772', '248', '51'),
('1773', '248', '52'),
('1774', '248', '53'),
('1775', '249', '47'),
('1776', '249', '48'),
('1777', '249', '49'),
('1778', '249', '50'),
('1779', '249', '51'),
('1780', '249', '52'),
('1781', '249', '53'),
('1782', '250', '47'),
('1783', '250', '48'),
('1784', '250', '49'),
('1785', '250', '50'),
('1786', '250', '51'),
('1787', '250', '52'),
('1788', '250', '53'),
('1789', '251', '47'),
('1790', '251', '48'),
('1791', '251', '49'),
('1792', '251', '50'),
('1793', '251', '51'),
('1794', '251', '52'),
('1795', '251', '53'),
('1796', '252', '47'),
('1797', '252', '48'),
('1798', '252', '49'),
('1799', '252', '50'),
('1800', '252', '51'),
('1801', '252', '52'),
('1802', '252', '53'),
('1803', '253', '47'),
('1804', '253', '48'),
('1805', '253', '49'),
('1806', '253', '50'),
('1807', '253', '51'),
('1808', '253', '52'),
('1809', '253', '53'),
('1810', '254', '47'),
('1811', '254', '48'),
('1812', '254', '49'),
('1813', '254', '50'),
('1814', '254', '51'),
('1815', '254', '52'),
('1816', '254', '53'),
('1817', '255', '47'),
('1818', '255', '48'),
('1819', '255', '49'),
('1820', '255', '50'),
('1821', '255', '51'),
('1822', '255', '52'),
('1823', '255', '53'),
('1824', '256', '47'),
('1825', '256', '48'),
('1826', '256', '49'),
('1827', '256', '50'),
('1828', '256', '51'),
('1829', '256', '52'),
('1830', '256', '53'),
('1831', '256', '47'),
('1832', '256', '48'),
('1833', '256', '49'),
('1834', '256', '50'),
('1835', '256', '51'),
('1836', '256', '52'),
('1837', '256', '53'),
('1838', '257', '47'),
('1839', '257', '48'),
('1840', '257', '49'),
('1841', '257', '50'),
('1842', '257', '51'),
('1843', '257', '52'),
('1844', '257', '53'),
('1845', '258', '47'),
('1846', '258', '48'),
('1847', '258', '49'),
('1848', '258', '50'),
('1849', '258', '51'),
('1850', '258', '52'),
('1851', '258', '53'),
('1852', '259', '47'),
('1853', '259', '48'),
('1854', '259', '49'),
('1855', '259', '50'),
('1856', '259', '51'),
('1857', '259', '52'),
('1858', '259', '53'),
('1859', '260', '47'),
('1860', '260', '48'),
('1861', '260', '49'),
('1862', '260', '50'),
('1863', '260', '51'),
('1864', '260', '52'),
('1865', '260', '53'),
('1866', '261', '47'),
('1867', '261', '48'),
('1868', '261', '49'),
('1869', '261', '50'),
('1870', '261', '51'),
('1871', '261', '52'),
('1872', '261', '53'),
('1873', '262', '47'),
('1874', '262', '48'),
('1875', '262', '49'),
('1876', '262', '50'),
('1877', '262', '51'),
('1878', '262', '52'),
('1879', '262', '53'),
('1880', '263', '47'),
('1881', '263', '48'),
('1882', '263', '49'),
('1883', '263', '50'),
('1884', '263', '51'),
('1885', '263', '52'),
('1886', '263', '53'),
('1887', '264', '47'),
('1888', '264', '48'),
('1889', '264', '49'),
('1890', '264', '50'),
('1891', '264', '51'),
('1892', '264', '52'),
('1893', '264', '53'),
('1894', '265', '47'),
('1895', '265', '48'),
('1896', '265', '49'),
('1897', '265', '50'),
('1898', '265', '51'),
('1899', '265', '52'),
('1900', '265', '53'),
('1901', '266', '47'),
('1902', '266', '48'),
('1903', '266', '49'),
('1904', '266', '50'),
('1905', '266', '51'),
('1906', '266', '52'),
('1907', '266', '53'),
('1908', '267', '47'),
('1909', '267', '48'),
('1910', '267', '49'),
('1911', '267', '50'),
('1912', '267', '51'),
('1913', '267', '52'),
('1914', '267', '53'),
('1915', '268', '47'),
('1916', '268', '48'),
('1917', '268', '49'),
('1918', '268', '50'),
('1919', '268', '51'),
('1920', '268', '52'),
('1921', '268', '53'),
('1922', '269', '47'),
('1923', '269', '48'),
('1924', '269', '49'),
('1925', '269', '50'),
('1926', '269', '51'),
('1927', '269', '52'),
('1928', '269', '53'),
('1929', '270', '47'),
('1930', '270', '48'),
('1931', '270', '49'),
('1932', '270', '50'),
('1933', '270', '51'),
('1934', '270', '52'),
('1935', '270', '53'),
('1936', '271', '47'),
('1937', '271', '48'),
('1938', '271', '49'),
('1939', '271', '50'),
('1940', '271', '51'),
('1941', '271', '52'),
('1942', '271', '53'),
('1943', '272', '47'),
('1944', '272', '48'),
('1945', '272', '49'),
('1946', '272', '50'),
('1947', '272', '51'),
('1948', '272', '52'),
('1949', '272', '53'),
('1950', '273', '47'),
('1951', '273', '48'),
('1952', '273', '49'),
('1953', '273', '50'),
('1954', '273', '51'),
('1955', '273', '52'),
('1956', '273', '53'),
('1957', '274', '47'),
('1958', '274', '48'),
('1959', '274', '49'),
('1960', '274', '50'),
('1961', '274', '51'),
('1962', '274', '52'),
('1963', '274', '53'),
('1964', '275', '47'),
('1965', '275', '48'),
('1966', '275', '49'),
('1967', '275', '50'),
('1968', '275', '51'),
('1969', '275', '52'),
('1970', '275', '53'),
('1971', '276', '47'),
('1972', '276', '48'),
('1973', '276', '49'),
('1974', '276', '50'),
('1975', '276', '51'),
('1976', '276', '52'),
('1977', '276', '53'),
('1978', '277', '47'),
('1979', '277', '48'),
('1980', '277', '49'),
('1981', '277', '50'),
('1982', '277', '51'),
('1983', '277', '52'),
('1984', '277', '53'),
('1985', '278', '47'),
('1986', '278', '48'),
('1987', '278', '49'),
('1988', '278', '50'),
('1989', '278', '51'),
('1990', '278', '52'),
('1991', '278', '53'),
('1992', '279', '47'),
('1993', '279', '48'),
('1994', '279', '49'),
('1995', '279', '50'),
('1996', '279', '51'),
('1997', '279', '52'),
('1998', '279', '53'),
('1999', '280', '47'),
('2000', '280', '48'),
('2001', '280', '49'),
('2002', '280', '50'),
('2003', '280', '51'),
('2004', '280', '52'),
('2005', '280', '53'),
('2006', '281', '47'),
('2007', '281', '48'),
('2008', '281', '49'),
('2009', '281', '50'),
('2010', '281', '51'),
('2011', '281', '52'),
('2012', '281', '53'),
('2013', '282', '47'),
('2014', '282', '48'),
('2015', '282', '49'),
('2016', '282', '50'),
('2017', '282', '51'),
('2018', '282', '52'),
('2019', '282', '53'),
('2020', '283', '47'),
('2021', '283', '48'),
('2022', '283', '49'),
('2023', '283', '50'),
('2024', '283', '51'),
('2025', '283', '52'),
('2026', '283', '53'),
('2027', '284', '35'),
('2028', '284', '36'),
('2029', '284', '37'),
('2030', '284', '38'),
('2031', '284', '39'),
('2032', '284', '40'),
('2033', '284', '41'),
('2034', '285', '35'),
('2035', '285', '36'),
('2036', '285', '37'),
('2037', '285', '38'),
('2038', '285', '39'),
('2039', '285', '40'),
('2040', '285', '41'),
('2041', '286', '35'),
('2042', '286', '36'),
('2043', '286', '37'),
('2044', '286', '38'),
('2045', '286', '39'),
('2046', '286', '40'),
('2047', '286', '41'),
('2048', '287', '35'),
('2049', '287', '36'),
('2050', '287', '37'),
('2051', '287', '38'),
('2052', '287', '39'),
('2053', '287', '40'),
('2054', '287', '41'),
('2055', '288', '35'),
('2056', '288', '36'),
('2057', '288', '37'),
('2058', '288', '38'),
('2059', '288', '39'),
('2060', '288', '40'),
('2061', '288', '41'),
('2062', '288', '35'),
('2063', '288', '36'),
('2064', '288', '37'),
('2065', '288', '38'),
('2066', '288', '39'),
('2067', '288', '40'),
('2068', '288', '41'),
('2069', '289', '35'),
('2070', '289', '36'),
('2071', '289', '37'),
('2072', '289', '38'),
('2073', '289', '39'),
('2074', '289', '40'),
('2075', '289', '41'),
('2076', '290', '35'),
('2077', '290', '36'),
('2078', '290', '37'),
('2079', '290', '38'),
('2080', '290', '39'),
('2081', '290', '40'),
('2082', '290', '41'),
('2083', '290', '35'),
('2084', '290', '36'),
('2085', '290', '37'),
('2086', '290', '38'),
('2087', '290', '39'),
('2088', '290', '40'),
('2089', '290', '41'),
('2090', '291', '35'),
('2091', '291', '36'),
('2092', '291', '37'),
('2093', '291', '38'),
('2094', '291', '39'),
('2095', '291', '40'),
('2096', '291', '41'),
('2097', '292', '35'),
('2098', '292', '36'),
('2099', '292', '37'),
('2100', '292', '38'),
('2101', '292', '39'),
('2102', '292', '40'),
('2103', '292', '41'),
('2104', '293', '35'),
('2105', '293', '36'),
('2106', '293', '37'),
('2107', '293', '38'),
('2108', '293', '39'),
('2109', '293', '40'),
('2110', '293', '41'),
('2111', '294', '35'),
('2112', '294', '36'),
('2113', '294', '37'),
('2114', '294', '38'),
('2115', '294', '39'),
('2116', '294', '40'),
('2117', '294', '41'),
('2118', '293', '35'),
('2119', '293', '36'),
('2120', '293', '37'),
('2121', '293', '38'),
('2122', '293', '39'),
('2123', '293', '40'),
('2124', '293', '41'),
('2125', '294', '35'),
('2126', '294', '36'),
('2127', '294', '37'),
('2128', '294', '38'),
('2129', '294', '39'),
('2130', '294', '40'),
('2131', '294', '41'),
('2132', '295', '35'),
('2133', '295', '36'),
('2134', '295', '37'),
('2135', '295', '38'),
('2136', '295', '39'),
('2137', '295', '40'),
('2138', '295', '41'),
('2139', '296', '35'),
('2140', '296', '36'),
('2141', '296', '37'),
('2142', '296', '38'),
('2143', '296', '39'),
('2144', '296', '40'),
('2145', '296', '41'),
('2146', '296', '35'),
('2147', '296', '36'),
('2148', '296', '37'),
('2149', '296', '38'),
('2150', '296', '39'),
('2151', '296', '40'),
('2152', '296', '41'),
('2153', '297', '35'),
('2154', '297', '36'),
('2155', '297', '37'),
('2156', '297', '38'),
('2157', '297', '39'),
('2158', '297', '40'),
('2159', '297', '41'),
('2160', '298', '35'),
('2161', '298', '36'),
('2162', '298', '37'),
('2163', '298', '38'),
('2164', '298', '39'),
('2165', '298', '40'),
('2166', '298', '41'),
('2167', '299', '35'),
('2168', '299', '36'),
('2169', '299', '37'),
('2170', '299', '38'),
('2171', '299', '39'),
('2172', '299', '40'),
('2173', '299', '41'),
('2174', '300', '35'),
('2175', '300', '36'),
('2176', '300', '37'),
('2177', '300', '38'),
('2178', '300', '39'),
('2179', '300', '40'),
('2180', '300', '41'),
('2181', '301', '35'),
('2182', '301', '36'),
('2183', '301', '37'),
('2184', '301', '38'),
('2185', '301', '39'),
('2186', '301', '40'),
('2187', '301', '41'),
('2188', '302', '35'),
('2189', '302', '36'),
('2190', '302', '37'),
('2191', '302', '38'),
('2192', '302', '39'),
('2193', '302', '40'),
('2194', '302', '41'),
('2195', '303', '35'),
('2196', '303', '36'),
('2197', '303', '37'),
('2198', '303', '38'),
('2199', '303', '39'),
('2200', '303', '40'),
('2201', '303', '41'),
('2202', '304', '35'),
('2203', '304', '36'),
('2204', '304', '37'),
('2205', '304', '38'),
('2206', '304', '39'),
('2207', '304', '40'),
('2208', '304', '41'),
('2209', '303', '35'),
('2210', '303', '36'),
('2211', '303', '37'),
('2212', '303', '38'),
('2213', '303', '39'),
('2214', '303', '40'),
('2215', '303', '41'),
('2216', '305', '35'),
('2217', '305', '36'),
('2218', '305', '37'),
('2219', '305', '38'),
('2220', '305', '39'),
('2221', '305', '40'),
('2222', '305', '41'),
('2223', '306', '35'),
('2224', '306', '36'),
('2225', '306', '37'),
('2226', '306', '38'),
('2227', '306', '39'),
('2228', '306', '40'),
('2229', '306', '41'),
('2230', '307', '35'),
('2231', '307', '36'),
('2232', '307', '37'),
('2233', '307', '38'),
('2234', '307', '39'),
('2235', '307', '40'),
('2236', '307', '41'),
('2237', '308', '35'),
('2238', '308', '36'),
('2239', '308', '37'),
('2240', '308', '38'),
('2241', '308', '39'),
('2242', '308', '40'),
('2243', '308', '41'),
('2244', '309', '35'),
('2245', '309', '36'),
('2246', '309', '37'),
('2247', '309', '38'),
('2248', '309', '39'),
('2249', '309', '40'),
('2250', '309', '41'),
('2251', '310', '35'),
('2252', '310', '36'),
('2253', '310', '37'),
('2254', '310', '38'),
('2255', '310', '39'),
('2256', '310', '40'),
('2257', '310', '41'),
('2258', '310', '35'),
('2259', '310', '36'),
('2260', '310', '37'),
('2261', '310', '38'),
('2262', '310', '39'),
('2263', '310', '40'),
('2264', '310', '41'),
('2265', '311', '35'),
('2266', '311', '36'),
('2267', '311', '37'),
('2268', '311', '38'),
('2269', '311', '39'),
('2270', '311', '40'),
('2271', '311', '41'),
('2272', '312', '35'),
('2273', '312', '36'),
('2274', '312', '37'),
('2275', '312', '38'),
('2276', '312', '39'),
('2277', '312', '40'),
('2278', '312', '41'),
('2279', '313', '35'),
('2280', '313', '36'),
('2281', '313', '37'),
('2282', '313', '38'),
('2283', '313', '39'),
('2284', '313', '40'),
('2285', '313', '41'),
('2286', '314', '35'),
('2287', '314', '36'),
('2288', '314', '37'),
('2289', '314', '38'),
('2290', '314', '39'),
('2291', '314', '40'),
('2292', '314', '41'),
('2293', '315', '35'),
('2294', '315', '36'),
('2295', '315', '37'),
('2296', '315', '38'),
('2297', '315', '39'),
('2298', '315', '40'),
('2299', '315', '41'),
('2300', '316', '35'),
('2301', '316', '36'),
('2302', '316', '37'),
('2303', '316', '38'),
('2304', '316', '39'),
('2305', '316', '40'),
('2306', '316', '41'),
('2307', '317', '35'),
('2308', '317', '36'),
('2309', '317', '37'),
('2310', '317', '38'),
('2311', '317', '39'),
('2312', '317', '40'),
('2313', '317', '41'),
('2314', '318', '35'),
('2315', '318', '36'),
('2316', '318', '37'),
('2317', '318', '38'),
('2318', '318', '39'),
('2319', '318', '40'),
('2320', '318', '41'),
('2321', '319', '35'),
('2322', '319', '36'),
('2323', '319', '37'),
('2324', '319', '38'),
('2325', '319', '39'),
('2326', '319', '40'),
('2327', '319', '41'),
('2328', '319', '35'),
('2329', '319', '36'),
('2330', '319', '37'),
('2331', '319', '38'),
('2332', '319', '39'),
('2333', '319', '40'),
('2334', '319', '41'),
('2335', '320', '35'),
('2336', '320', '36'),
('2337', '320', '37'),
('2338', '320', '38'),
('2339', '320', '39'),
('2340', '320', '40'),
('2341', '320', '41'),
('2342', '321', '35'),
('2343', '321', '36'),
('2344', '321', '37'),
('2345', '321', '38'),
('2346', '321', '39'),
('2347', '321', '40'),
('2348', '321', '41'),
('2349', '322', '35'),
('2350', '322', '36'),
('2351', '322', '37'),
('2352', '322', '38'),
('2353', '322', '39'),
('2354', '322', '40'),
('2355', '322', '41'),
('2356', '322', '35'),
('2357', '322', '36'),
('2358', '322', '37'),
('2359', '322', '38'),
('2360', '322', '39'),
('2361', '322', '40'),
('2362', '322', '41'),
('2363', '323', '35'),
('2364', '323', '36'),
('2365', '323', '37'),
('2366', '323', '38'),
('2367', '323', '39'),
('2368', '323', '40'),
('2369', '323', '41'),
('2370', '324', '35'),
('2371', '324', '36'),
('2372', '324', '37'),
('2373', '324', '38'),
('2374', '324', '39'),
('2375', '324', '40'),
('2376', '324', '41'),
('2377', '325', '35'),
('2378', '325', '36'),
('2379', '325', '37'),
('2380', '325', '38'),
('2381', '325', '39'),
('2382', '325', '40'),
('2383', '325', '41'),
('2384', '326', '35'),
('2385', '326', '36'),
('2386', '326', '37'),
('2387', '326', '38'),
('2388', '326', '39'),
('2389', '326', '40'),
('2390', '326', '41'),
('2391', '327', '35'),
('2392', '327', '36'),
('2393', '327', '37'),
('2394', '327', '38'),
('2395', '327', '39'),
('2396', '327', '40'),
('2397', '327', '41'),
('2398', '328', '35'),
('2399', '328', '36'),
('2400', '328', '37'),
('2401', '328', '38'),
('2402', '328', '39'),
('2403', '328', '40'),
('2404', '328', '41'),
('2405', '329', '35'),
('2406', '329', '36'),
('2407', '329', '37'),
('2408', '329', '38'),
('2409', '329', '39'),
('2410', '329', '40'),
('2411', '329', '41'),
('2412', '330', '35'),
('2413', '330', '36'),
('2414', '330', '37'),
('2415', '330', '38'),
('2416', '330', '39'),
('2417', '330', '40'),
('2418', '330', '41'),
('2419', '331', '35'),
('2420', '331', '36'),
('2421', '331', '37'),
('2422', '331', '38'),
('2423', '331', '39'),
('2424', '331', '40'),
('2425', '331', '41'),
('2426', '332', '35'),
('2427', '332', '36'),
('2428', '332', '37'),
('2429', '332', '38'),
('2430', '332', '39'),
('2431', '332', '40'),
('2432', '332', '41'),
('2433', '332', '35'),
('2434', '332', '36'),
('2435', '332', '37'),
('2436', '332', '38'),
('2437', '332', '39'),
('2438', '332', '40'),
('2439', '332', '41'),
('2440', '333', '35'),
('2441', '333', '36'),
('2442', '333', '37'),
('2443', '333', '38'),
('2444', '333', '39'),
('2445', '333', '40'),
('2446', '333', '41'),
('2447', '334', '35'),
('2448', '334', '36'),
('2449', '334', '37'),
('2450', '334', '38'),
('2451', '334', '39'),
('2452', '334', '40'),
('2453', '334', '41'),
('2454', '334', '35'),
('2455', '334', '36'),
('2456', '334', '37'),
('2457', '334', '38'),
('2458', '334', '39'),
('2459', '334', '40'),
('2460', '334', '41'),
('2461', '335', '35'),
('2462', '335', '36'),
('2463', '335', '37'),
('2464', '335', '38'),
('2465', '335', '39'),
('2466', '335', '40'),
('2467', '335', '41'),
('2468', '336', '35'),
('2469', '336', '36'),
('2470', '336', '37'),
('2471', '336', '38'),
('2472', '336', '39'),
('2473', '336', '40'),
('2474', '336', '41'),
('2475', '337', '35'),
('2476', '337', '36'),
('2477', '337', '37'),
('2478', '337', '38'),
('2479', '337', '39'),
('2480', '337', '40'),
('2481', '337', '41'),
('2482', '338', '35'),
('2483', '338', '36'),
('2484', '338', '37'),
('2485', '338', '38'),
('2486', '338', '39'),
('2487', '338', '40'),
('2488', '338', '41'),
('2489', '339', '35'),
('2490', '339', '36'),
('2491', '339', '37'),
('2492', '339', '38'),
('2493', '339', '39'),
('2494', '339', '40'),
('2495', '339', '41'),
('2496', '340', '35'),
('2497', '340', '36'),
('2498', '340', '37'),
('2499', '340', '38'),
('2500', '340', '39'),
('2501', '340', '40'),
('2502', '340', '41'),
('2503', '341', '35'),
('2504', '341', '36'),
('2505', '341', '37'),
('2506', '341', '38'),
('2507', '341', '39'),
('2508', '341', '40'),
('2509', '341', '41'),
('2510', '342', '35'),
('2511', '342', '36'),
('2512', '342', '37'),
('2513', '342', '38'),
('2514', '342', '39'),
('2515', '342', '40'),
('2516', '342', '41'),
('2517', '343', '35'),
('2518', '343', '36'),
('2519', '343', '37'),
('2520', '343', '38'),
('2521', '343', '39'),
('2522', '343', '40'),
('2523', '343', '41'),
('2524', '344', '35'),
('2525', '344', '36'),
('2526', '344', '37'),
('2527', '344', '38'),
('2528', '344', '39'),
('2529', '344', '40'),
('2530', '344', '41'),
('2531', '345', '35'),
('2532', '345', '36'),
('2533', '345', '37'),
('2534', '345', '38'),
('2535', '345', '39'),
('2536', '345', '40'),
('2537', '345', '41'),
('2538', '346', '35'),
('2539', '346', '36'),
('2540', '346', '37'),
('2541', '346', '38'),
('2542', '346', '39'),
('2543', '346', '40'),
('2544', '346', '41'),
('2545', '347', '35'),
('2546', '347', '36'),
('2547', '347', '37'),
('2548', '347', '38'),
('2549', '347', '39'),
('2550', '347', '40'),
('2551', '347', '41'),
('2552', '348', '35'),
('2553', '348', '36'),
('2554', '348', '37'),
('2555', '348', '38'),
('2556', '348', '39'),
('2557', '348', '40'),
('2558', '348', '41'),
('2559', '349', '35'),
('2560', '349', '36'),
('2561', '349', '37'),
('2562', '349', '38'),
('2563', '349', '39'),
('2564', '349', '40'),
('2565', '349', '41'),
('2566', '350', '35'),
('2567', '350', '36'),
('2568', '350', '37'),
('2569', '350', '38'),
('2570', '350', '39'),
('2571', '350', '40'),
('2572', '350', '41'),
('2573', '349', '35'),
('2574', '349', '36'),
('2575', '349', '37'),
('2576', '349', '38'),
('2577', '349', '39'),
('2578', '349', '40'),
('2579', '349', '41'),
('2580', '351', '35'),
('2581', '351', '36'),
('2582', '351', '37'),
('2583', '351', '38'),
('2584', '351', '39'),
('2585', '351', '40'),
('2586', '351', '41'),
('2587', '352', '35'),
('2588', '352', '36'),
('2589', '352', '37'),
('2590', '352', '38'),
('2591', '352', '39'),
('2592', '352', '40'),
('2593', '352', '41'),
('2594', '353', '35'),
('2595', '353', '36'),
('2596', '353', '37'),
('2597', '353', '38'),
('2598', '353', '39'),
('2599', '353', '40'),
('2600', '353', '41'),
('2601', '354', '35'),
('2602', '354', '36'),
('2603', '354', '37'),
('2604', '354', '38'),
('2605', '354', '39'),
('2606', '354', '40'),
('2607', '354', '41'),
('2608', '355', '35'),
('2609', '355', '36'),
('2610', '355', '37'),
('2611', '355', '38'),
('2612', '355', '39'),
('2613', '355', '40'),
('2614', '355', '41'),
('2615', '355', '35'),
('2616', '355', '36'),
('2617', '355', '37'),
('2618', '355', '38'),
('2619', '355', '39'),
('2620', '355', '40'),
('2621', '355', '41'),
('2622', '356', '35'),
('2623', '356', '36'),
('2624', '356', '37'),
('2625', '356', '38'),
('2626', '356', '39'),
('2627', '356', '40'),
('2628', '356', '41'),
('2629', '357', '35'),
('2630', '357', '36'),
('2631', '357', '37'),
('2632', '357', '38'),
('2633', '357', '39'),
('2634', '357', '40'),
('2635', '357', '41'),
('2636', '358', '35'),
('2637', '358', '36'),
('2638', '358', '37'),
('2639', '358', '38'),
('2640', '358', '39'),
('2641', '358', '40'),
('2642', '358', '41'),
('2643', '359', '35'),
('2644', '359', '36'),
('2645', '359', '37'),
('2646', '359', '38'),
('2647', '359', '39'),
('2648', '359', '40'),
('2649', '359', '41'),
('2650', '360', '35'),
('2651', '360', '36'),
('2652', '360', '37'),
('2653', '360', '38'),
('2654', '360', '39'),
('2655', '360', '40'),
('2656', '360', '41'),
('2657', '361', '35'),
('2658', '361', '36'),
('2659', '361', '37'),
('2660', '361', '38'),
('2661', '361', '39'),
('2662', '361', '40'),
('2663', '361', '41'),
('2664', '362', '35'),
('2665', '362', '36'),
('2666', '362', '37'),
('2667', '362', '38'),
('2668', '362', '39'),
('2669', '362', '40'),
('2670', '362', '41'),
('2671', '210', '42'),
('2672', '210', '43'),
('2673', '210', '44'),
('2674', '210', '45'),
('2675', '210', '46'),
('2676', '211', '42'),
('2677', '211', '43'),
('2678', '211', '44'),
('2679', '211', '45'),
('2680', '211', '46'),
('2681', '363', '42'),
('2682', '363', '43'),
('2683', '363', '44'),
('2684', '363', '45'),
('2685', '363', '46'),
('2686', '364', '42'),
('2687', '364', '43'),
('2688', '364', '44'),
('2689', '364', '45'),
('2690', '364', '46'),
('2691', '365', '42'),
('2692', '365', '43'),
('2693', '365', '44'),
('2694', '365', '45'),
('2695', '365', '46'),
('2696', '366', '42'),
('2697', '366', '43'),
('2698', '366', '44'),
('2699', '366', '45'),
('2700', '366', '46'),
('2701', '365', '42'),
('2702', '365', '43'),
('2703', '365', '44'),
('2704', '365', '45'),
('2705', '365', '46'),
('2706', '366', '42'),
('2707', '366', '43'),
('2708', '366', '44'),
('2709', '366', '45'),
('2710', '366', '46'),
('2711', '250', '47'),
('2712', '250', '48'),
('2713', '250', '49'),
('2714', '250', '50'),
('2715', '250', '51'),
('2716', '250', '52'),
('2717', '250', '53'),
('2718', '259', '47'),
('2719', '259', '48'),
('2720', '259', '49'),
('2721', '259', '50'),
('2722', '259', '51'),
('2723', '259', '52'),
('2724', '259', '53'),
('2725', '258', '47'),
('2726', '258', '48'),
('2727', '258', '49'),
('2728', '258', '50'),
('2729', '258', '51'),
('2730', '258', '52'),
('2731', '258', '53'),
('2732', '257', '47'),
('2733', '257', '48'),
('2734', '257', '49'),
('2735', '257', '50'),
('2736', '257', '51'),
('2737', '257', '52'),
('2738', '257', '53'),
('2739', '256', '47'),
('2740', '256', '48'),
('2741', '256', '49'),
('2742', '256', '50'),
('2743', '256', '51'),
('2744', '256', '52'),
('2745', '256', '53'),
('2746', '252', '47'),
('2747', '252', '48'),
('2748', '252', '49'),
('2749', '252', '50'),
('2750', '252', '51'),
('2751', '252', '52'),
('2752', '252', '53'),
('2753', '261', '47'),
('2754', '261', '48'),
('2755', '261', '49'),
('2756', '261', '50'),
('2757', '261', '51'),
('2758', '261', '52'),
('2759', '261', '53'),
('2760', '265', '47'),
('2761', '265', '48'),
('2762', '265', '49'),
('2763', '265', '50'),
('2764', '265', '51'),
('2765', '265', '52'),
('2766', '265', '53'),
('2767', '277', '47'),
('2768', '277', '48'),
('2769', '277', '49'),
('2770', '277', '50'),
('2771', '277', '51'),
('2772', '277', '52'),
('2773', '277', '53'),
('2774', '273', '47'),
('2775', '273', '48'),
('2776', '273', '49'),
('2777', '273', '50'),
('2778', '273', '51'),
('2779', '273', '52'),
('2780', '273', '53'),
('2781', '270', '47'),
('2782', '270', '48'),
('2783', '270', '49'),
('2784', '270', '50'),
('2785', '270', '51'),
('2786', '270', '52'),
('2787', '270', '53'),
('2788', '268', '47'),
('2789', '268', '48'),
('2790', '268', '49'),
('2791', '268', '50'),
('2792', '268', '51'),
('2793', '268', '52'),
('2794', '268', '53'),
('2795', '283', '47'),
('2796', '283', '48'),
('2797', '283', '49'),
('2798', '283', '50'),
('2799', '283', '51'),
('2800', '283', '52'),
('2801', '283', '53'),
('2802', '10', '28'),
('2803', '10', '29'),
('2804', '10', '30'),
('2805', '10', '31'),
('2806', '10', '32'),
('2807', '10', '33'),
('2808', '10', '34'),
('2809', '10', '28'),
('2810', '10', '29'),
('2811', '10', '30'),
('2812', '10', '31'),
('2813', '10', '32'),
('2814', '10', '33'),
('2815', '10', '34'),
('2816', '26', '28'),
('2817', '26', '29'),
('2818', '26', '30'),
('2819', '26', '31'),
('2820', '26', '32'),
('2821', '26', '33'),
('2822', '26', '34'),
('2823', '197', '42'),
('2824', '197', '43'),
('2825', '197', '44'),
('2826', '197', '45'),
('2827', '197', '46'),
('2828', '198', '42'),
('2829', '198', '43'),
('2830', '198', '44'),
('2831', '198', '45'),
('2832', '198', '46'),
('2833', '199', '42'),
('2834', '199', '43'),
('2835', '199', '44'),
('2836', '199', '45'),
('2837', '199', '46'),
('2838', '200', '42'),
('2839', '200', '43'),
('2840', '200', '44'),
('2841', '200', '45'),
('2842', '200', '46'),
('2843', '201', '42'),
('2844', '201', '43'),
('2845', '201', '44'),
('2846', '201', '45'),
('2847', '201', '46'),
('2848', '202', '42'),
('2849', '202', '43'),
('2850', '202', '44'),
('2851', '202', '45'),
('2852', '202', '46'),
('2853', '203', '42'),
('2854', '203', '43'),
('2855', '203', '44'),
('2856', '203', '45'),
('2857', '203', '46'),
('2858', '204', '42'),
('2859', '204', '43'),
('2860', '204', '44'),
('2861', '204', '45'),
('2862', '204', '46'),
('2863', '205', '42'),
('2864', '205', '43'),
('2865', '205', '44'),
('2866', '205', '45'),
('2867', '205', '46'),
('2868', '206', '42'),
('2869', '206', '43'),
('2870', '206', '44'),
('2871', '206', '45'),
('2872', '206', '46'),
('2873', '207', '42'),
('2874', '207', '43'),
('2875', '207', '44'),
('2876', '207', '45'),
('2877', '207', '46'),
('2878', '208', '42'),
('2879', '208', '43'),
('2880', '208', '44'),
('2881', '208', '45'),
('2882', '208', '46');

-- ------------------------------------------------------------

--
-- Table structure for table `carte_format`
--

CREATE TABLE `carte_format` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_carte` int(11) NOT NULL,
  `id_format` int(11) NOT NULL,
  `prix` double NOT NULL,
  `temps_preparation` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_carte_format_carte` (`id_carte`),
  KEY `fk_carte_format_format` (`id_format`),
  CONSTRAINT `fk_carte_format_carte` FOREIGN KEY (`id_carte`) REFERENCES `carte` (`id`),
  CONSTRAINT `fk_carte_format_format` FOREIGN KEY (`id_format`) REFERENCES `restaurant_format` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=434 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `carte_format`
--

INSERT INTO `carte_format` (`id`, `id_carte`, `id_format`, `prix`, `temps_preparation`) VALUES
('1', '1', '1', '7', '10'),
('3', '3', '4', '2', '0'),
('4', '4', '4', '2', '0'),
('5', '2', '4', '19.9', '10'),
('6', '5', '1', '5', '5'),
('7', '6', '1', '6', '10'),
('8', '7', '1', '7', '10'),
('9', '8', '1', '5', '10'),
('10', '9', '1', '5', '3'),
('14', '11', '5', '6.5', '10'),
('15', '12', '5', '7', '10'),
('16', '13', '5', '6', '10'),
('17', '14', '5', '6', '10'),
('18', '15', '5', '16', '10'),
('19', '16', '5', '6', '10'),
('20', '17', '5', '8', '10'),
('21', '18', '5', '4.5', '10'),
('22', '19', '5', '4', '10'),
('23', '20', '5', '7', '10'),
('24', '21', '5', '4.5', '10'),
('25', '22', '5', '3.5', '10'),
('26', '23', '5', '4.5', '10'),
('27', '24', '5', '5.5', '10'),
('28', '25', '5', '5.5', '10'),
('30', '27', '5', '19', '10'),
('31', '28', '5', '6.5', '10'),
('32', '29', '5', '12.5', '10'),
('33', '30', '5', '1.5', '10'),
('34', '31', '5', '1.5', '5'),
('35', '32', '5', '3.5', '10'),
('37', '33', '5', '3.8', '10'),
('38', '34', '5', '4', '10'),
('39', '35', '5', '4', '5'),
('40', '36', '5', '4', '5'),
('41', '37', '5', '5', '5'),
('42', '38', '5', '8', '10'),
('43', '39', '5', '8', '5'),
('44', '40', '5', '8.5', '10'),
('45', '41', '5', '9.5', '10'),
('46', '42', '5', '9.5', '10'),
('47', '43', '5', '9.5', '5'),
('48', '44', '5', '9.5', '5'),
('49', '45', '5', '10', '5'),
('50', '46', '5', '10', '5'),
('51', '47', '5', '10', '5'),
('52', '48', '5', '10', '5'),
('53', '49', '5', '10', '5'),
('54', '50', '5', '11.5', '5'),
('55', '51', '5', '11.5', '5'),
('56', '52', '5', '9', '5'),
('57', '53', '5', '10', '5'),
('58', '54', '5', '10.5', '5'),
('59', '55', '5', '11', '5'),
('60', '56', '5', '10.5', '5'),
('61', '57', '5', '11', '5'),
('62', '58', '5', '11', '5'),
('63', '59', '5', '9.5', '5'),
('64', '60', '5', '9.5', '5'),
('65', '61', '5', '10.5', '5'),
('66', '62', '5', '10.5', '5'),
('67', '63', '5', '12', '5'),
('68', '64', '5', '12', '5'),
('69', '65', '5', '12', '5'),
('70', '66', '5', '12', '5'),
('71', '67', '5', '12', '5'),
('72', '68', '5', '12', '8'),
('73', '69', '5', '12', '5'),
('74', '70', '5', '13', '5'),
('75', '71', '5', '13', '5'),
('76', '72', '5', '15', '5'),
('77', '73', '5', '9', '5'),
('78', '74', '5', '10', '10'),
('79', '75', '5', '11.5', '5'),
('80', '76', '5', '9.5', '5'),
('81', '77', '5', '10', '5'),
('82', '78', '5', '10', '5'),
('83', '79', '5', '18', '5'),
('84', '80', '5', '6.5', '5'),
('85', '81', '5', '6.5', '5'),
('86', '82', '5', '6.5', '5'),
('87', '83', '5', '6.5', '5'),
('88', '84', '5', '7', '5'),
('89', '85', '5', '7', '5'),
('90', '86', '5', '7', '5'),
('91', '87', '5', '7', '5'),
('92', '88', '5', '8', '5'),
('93', '89', '5', '3', '5'),
('94', '90', '5', '4', '5'),
('95', '91', '5', '4', '5'),
('96', '92', '5', '4.1', '5'),
('97', '93', '5', '6.5', '5'),
('98', '94', '6', '3.5', '5'),
('99', '94', '7', '3.5', '5'),
('100', '94', '8', '3.5', '5'),
('101', '95', '9', '4.5', '5'),
('102', '95', '10', '4.5', '5'),
('103', '96', '5', '5', '5'),
('104', '97', '5', '7', '5'),
('105', '98', '5', '5', '5'),
('106', '99', '5', '2', '5'),
('107', '100', '5', '2', '5'),
('108', '101', '5', '2', '5'),
('109', '102', '5', '2', '5'),
('110', '103', '5', '3', '5'),
('111', '104', '11', '10', '0'),
('112', '104', '12', '18', '0'),
('113', '105', '11', '10', '0'),
('114', '105', '12', '18', '0'),
('115', '106', '13', '3.5', '0'),
('116', '106', '14', '6', '0'),
('117', '107', '13', '3.5', '0'),
('118', '107', '14', '6', '0'),
('119', '108', '5', '3.5', '0'),
('120', '109', '5', '3.5', '0'),
('121', '110', '5', '3.5', '0'),
('122', '111', '11', '11', '0'),
('123', '111', '12', '22', '0'),
('124', '112', '11', '15', '0'),
('125', '112', '12', '28', '0'),
('126', '113', '11', '14', '0'),
('127', '113', '12', '25', '0'),
('128', '114', '15', '12.9', '10'),
('129', '115', '15', '17', '4'),
('130', '116', '15', '16.9', '4'),
('132', '118', '15', '15.5', '5'),
('133', '117', '15', '13.5', '5'),
('134', '119', '15', '15.5', '5'),
('135', '120', '15', '15.9', '5'),
('136', '121', '15', '15.9', '5'),
('137', '122', '15', '17.9', '5'),
('138', '123', '15', '12.95', '5'),
('139', '124', '15', '15.95', '5'),
('140', '125', '15', '17.5', '5'),
('141', '126', '15', '13.9', '5'),
('142', '127', '15', '15.9', '5'),
('143', '128', '15', '16.8', '5'),
('144', '129', '15', '16.4', '5'),
('145', '130', '15', '15.5', '10'),
('146', '131', '15', '8.5', '5'),
('147', '132', '15', '9.5', '5'),
('148', '133', '15', '13.9', '5'),
('149', '134', '15', '14.5', '5'),
('150', '135', '15', '14.5', '5'),
('151', '136', '15', '14.5', '5'),
('152', '137', '15', '17.5', '5'),
('153', '138', '15', '11.5', '5'),
('154', '139', '15', '11.8', '5'),
('155', '140', '15', '15.9', '5'),
('156', '141', '15', '13.5', '5'),
('157', '142', '15', '46.5', '20'),
('158', '143', '15', '49.5', '20'),
('160', '144', '15', '69', '0'),
('161', '145', '15', '2', '2'),
('162', '166', '15', '12.5', '20'),
('163', '167', '15', '14.9', '20'),
('164', '168', '15', '13.5', '20'),
('165', '169', '15', '15.5', '20'),
('166', '170', '15', '1.95', '5'),
('167', '171', '15', '1.95', '5'),
('168', '172', '15', '6', '5'),
('169', '173', '15', '6.9', '5'),
('170', '174', '15', '6.5', '5'),
('172', '176', '15', '7', '5'),
('173', '175', '15', '6.5', '5'),
('174', '177', '15', '1.95', '5'),
('175', '178', '15', '2.6', '5'),
('176', '179', '15', '5', '5'),
('178', '180', '15', '4.1', '5'),
('179', '181', '15', '3.95', '5'),
('180', '182', '15', '5.9', '5'),
('181', '183', '15', '5.9', '5'),
('182', '184', '15', '5.9', '5'),
('183', '185', '15', '4.8', '0'),
('184', '186', '15', '4', '0'),
('185', '187', '15', '4', '0'),
('187', '188', '15', '4.7', '0'),
('188', '189', '15', '4', '0'),
('189', '190', '15', '4.5', '0'),
('190', '191', '15', '5.2', '0'),
('191', '192', '15', '5.2', '0'),
('192', '193', '15', '5.2', '0'),
('193', '194', '15', '5.2', '0'),
('194', '195', '15', '4.5', '0'),
('197', '196', '16', '1.5', '0'),
('212', '209', '17', '12.5', '10'),
('213', '209', '18', '16.5', '15'),
('216', '212', '16', '11', '20'),
('217', '213', '16', '11', '20'),
('218', '214', '16', '13', '20'),
('219', '215', '16', '3', '1'),
('220', '216', '16', '3', '1'),
('221', '217', '16', '15', '1'),
('222', '218', '16', '15', '1'),
('223', '219', '19', '3.5', '1'),
('224', '220', '19', '3.5', '1'),
('225', '221', '19', '4.5', '0'),
('226', '222', '19', '4.5', '0'),
('228', '223', '19', '3.5', '1'),
('229', '224', '19', '3.5', '1'),
('230', '225', '19', '3.5', '1'),
('231', '226', '19', '3.5', '1'),
('232', '227', '19', '15', '1'),
('233', '228', '19', '19', '1'),
('234', '229', '19', '19.5', '1'),
('235', '230', '19', '28', '1'),
('236', '231', '19', '23', '1'),
('237', '232', '19', '29', '1'),
('238', '233', '19', '42', '1'),
('239', '234', '19', '22.5', '1'),
('240', '235', '19', '29', '0'),
('241', '236', '19', '15', '1'),
('242', '237', '19', '14.5', '1'),
('243', '238', '19', '19.5', '1'),
('244', '239', '19', '20.5', '1'),
('245', '240', '19', '7', '5'),
('246', '241', '19', '7', '0'),
('247', '242', '19', '10.2', '5'),
('248', '243', '19', '10.8', '5'),
('249', '244', '19', '11', '5'),
('250', '245', '19', '10.2', '5'),
('251', '246', '19', '3', '5'),
('252', '247', '19', '4.5', '5'),
('253', '248', '19', '15.5', '5'),
('254', '249', '19', '13.1', '5'),
('256', '251', '19', '11', '5'),
('258', '253', '19', '10.5', '5'),
('259', '254', '19', '10.5', '5'),
('260', '255', '19', '11', '5'),
('266', '260', '19', '12.5', '5'),
('268', '262', '19', '15.1', '5'),
('269', '263', '19', '15.5', '5'),
('270', '264', '19', '14', '5'),
('272', '266', '19', '10.5', '5'),
('273', '267', '19', '11.8', '5'),
('275', '269', '19', '12', '5'),
('277', '271', '19', '12.3', '5'),
('278', '272', '19', '12.3', '5'),
('280', '274', '19', '12.3', '5'),
('281', '275', '19', '12.3', '5'),
('282', '276', '19', '12.5', '5'),
('284', '278', '19', '13.5', '5'),
('285', '279', '19', '5.8', '5'),
('286', '280', '19', '5.8', '5'),
('287', '281', '19', '5.8', '5'),
('288', '282', '19', '5.9', '5'),
('290', '285', '15', '9.2', '5'),
('291', '286', '15', '8.8', '5'),
('292', '287', '15', '7.8', '5'),
('294', '288', '15', '9.8', '5'),
('295', '289', '15', '11.5', '5'),
('297', '290', '15', '11.5', '5'),
('298', '291', '15', '4.5', '5'),
('299', '292', '15', '3.9', '5'),
('302', '293', '15', '3.7', '5'),
('303', '294', '15', '3.7', '5'),
('304', '295', '15', '3.7', '5'),
('306', '296', '15', '5.9', '5'),
('307', '297', '15', '6', '5'),
('308', '298', '15', '6', '5'),
('309', '299', '15', '5.5', '5'),
('310', '300', '15', '11.5', '5'),
('311', '301', '15', '4.8', '5'),
('312', '302', '15', '4.5', '5'),
('314', '304', '15', '4.8', '5'),
('315', '303', '15', '4.5', '5'),
('316', '305', '15', '6.3', '5'),
('317', '306', '15', '6.3', '5'),
('318', '307', '15', '6.3', '5'),
('319', '308', '15', '6.3', '5'),
('320', '309', '15', '6.3', '5'),
('322', '310', '15', '6.3', '5'),
('323', '311', '15', '6.3', '5'),
('324', '312', '15', '5.7', '5'),
('325', '313', '15', '5.7', '5'),
('326', '314', '15', '5.7', '5'),
('327', '315', '15', '5.7', '5'),
('328', '316', '15', '5.7', '5'),
('329', '317', '15', '5.7', '5'),
('330', '318', '15', '5.7', '5'),
('332', '319', '15', '5.2', '5'),
('333', '320', '15', '5.2', '5'),
('334', '321', '15', '5.2', '5'),
('336', '322', '15', '5.2', '5'),
('337', '323', '15', '6.5', '5'),
('338', '324', '15', '6.5', '5'),
('339', '325', '15', '6.9', '5'),
('340', '326', '15', '6', '5'),
('341', '327', '15', '6.5', '5'),
('342', '328', '15', '6', '4'),
('343', '329', '15', '4.65', '5'),
('344', '330', '15', '4.65', '5'),
('345', '331', '15', '4.65', '5'),
('347', '332', '15', '3.6', '5'),
('348', '333', '15', '3.8', '5'),
('350', '334', '15', '3.8', '5'),
('351', '335', '15', '4.8', '5'),
('352', '336', '15', '4', '5'),
('353', '337', '15', '3.6', '5'),
('354', '338', '15', '3.6', '5'),
('355', '339', '15', '5.8', '5'),
('356', '340', '15', '4', '5'),
('357', '341', '15', '5.8', '5'),
('358', '342', '15', '3.9', '5'),
('359', '343', '15', '3.9', '5'),
('360', '344', '15', '3.9', '5'),
('361', '345', '15', '3.9', '5'),
('362', '346', '15', '3.9', '5'),
('363', '347', '15', '3.9', '5'),
('364', '348', '15', '3.9', '5'),
('366', '350', '15', '2.8', '5'),
('367', '349', '15', '2.8', '5'),
('368', '351', '15', '2.8', '5'),
('369', '352', '15', '2.8', '5'),
('370', '353', '15', '2.8', '5'),
('371', '354', '15', '3.9', '5'),
('373', '355', '15', '3.9', '5'),
('374', '356', '15', '3.9', '5'),
('375', '357', '15', '3.9', '5'),
('376', '358', '20', '9', '5'),
('377', '358', '21', '13.5', '5'),
('378', '359', '20', '37.5', '5'),
('379', '359', '21', '13.5', '2'),
('380', '360', '20', '8.5', '5'),
('381', '360', '21', '11.9', '5'),
('382', '361', '20', '9.5', '5'),
('383', '361', '21', '15.9', '5'),
('384', '362', '20', '9', '5'),
('385', '362', '21', '13.5', '5'),
('386', '210', '16', '13', '20'),
('387', '211', '16', '13', '20'),
('388', '363', '16', '18', '5'),
('389', '364', '16', '15', '0'),
('392', '365', '16', '6', '0'),
('393', '366', '16', '6', '2'),
('394', '250', '19', '10', '5'),
('395', '259', '19', '12.5', '5'),
('396', '258', '19', '12.5', '5'),
('397', '257', '19', '12.5', '5'),
('398', '256', '19', '11.5', '5'),
('399', '252', '19', '11', '5'),
('400', '261', '19', '16.5', '5'),
('401', '265', '19', '10.3', '5'),
('402', '277', '19', '13.5', '5'),
('403', '273', '19', '11.8', '5'),
('404', '270', '19', '11.8', '5'),
('405', '268', '19', '11.8', '5'),
('406', '283', '19', '5.6', '5'),
('408', '10', '5', '5.5', '10'),
('409', '26', '5', '10', '10'),
('410', '197', '22', '14', '20'),
('411', '197', '23', '12.5', '20'),
('412', '198', '22', '13', '20'),
('413', '198', '23', '11.5', '20'),
('414', '199', '22', '12', '20'),
('415', '199', '23', '10.5', '20'),
('416', '200', '22', '13', '20'),
('417', '200', '23', '11.5', '20'),
('418', '201', '22', '13', '20'),
('419', '201', '23', '11.5', '20'),
('420', '202', '22', '14', '20'),
('421', '202', '23', '12.5', '20'),
('422', '203', '22', '14', '20'),
('423', '203', '23', '12.5', '20'),
('424', '204', '22', '14', '20'),
('425', '204', '23', '12.5', '20'),
('426', '205', '22', '14', '20'),
('427', '205', '23', '12.5', '20'),
('428', '206', '22', '14', '20'),
('429', '206', '23', '12.5', '20'),
('430', '207', '22', '14', '20'),
('431', '207', '23', '12.5', '20'),
('432', '208', '22', '14', '20'),
('433', '208', '23', '12.5', '20');

-- ------------------------------------------------------------

--
-- Table structure for table `carte_option`
--

CREATE TABLE `carte_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_carte` int(11) NOT NULL,
  `id_option` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_carte_option_carte` (`id_carte`),
  KEY `fk_carte_option_restaurant_option` (`id_option`),
  CONSTRAINT `fk_carte_option_carte` FOREIGN KEY (`id_carte`) REFERENCES `carte` (`id`),
  CONSTRAINT `fk_carte_option_restaurant_option` FOREIGN KEY (`id_option`) REFERENCES `restaurant_option` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=94 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `carte_option`
--

INSERT INTO `carte_option` (`id`, `id_carte`, `id_option`) VALUES
('1', '2', '1'),
('2', '6', '2'),
('3', '10', '3'),
('4', '11', '3'),
('5', '12', '3'),
('6', '13', '3'),
('7', '14', '3'),
('8', '15', '3'),
('9', '16', '3'),
('10', '17', '3'),
('11', '18', '3'),
('12', '19', '3'),
('13', '20', '3'),
('14', '21', '3'),
('15', '22', '3'),
('16', '23', '3'),
('17', '24', '3'),
('18', '25', '3'),
('19', '26', '3'),
('20', '27', '3'),
('21', '28', '3'),
('22', '29', '3'),
('24', '38', '3'),
('25', '39', '3'),
('26', '40', '3'),
('27', '41', '3'),
('28', '42', '3'),
('29', '43', '3'),
('30', '44', '3'),
('31', '45', '3'),
('32', '46', '3'),
('33', '47', '3'),
('34', '48', '3'),
('35', '49', '3'),
('36', '50', '3'),
('37', '51', '3'),
('38', '52', '3'),
('39', '53', '3'),
('40', '54', '3'),
('41', '55', '3'),
('42', '56', '3'),
('43', '57', '3'),
('44', '58', '3'),
('45', '59', '3'),
('46', '60', '3'),
('47', '61', '3'),
('48', '62', '3'),
('49', '63', '3'),
('50', '64', '3'),
('51', '65', '3'),
('52', '66', '3'),
('53', '67', '3'),
('54', '68', '3'),
('55', '69', '3'),
('56', '70', '3'),
('57', '71', '3'),
('58', '72', '3'),
('59', '73', '3'),
('60', '74', '3'),
('61', '75', '3'),
('62', '76', '3'),
('63', '77', '3'),
('64', '78', '3'),
('65', '79', '3'),
('66', '80', '3'),
('67', '81', '3'),
('68', '82', '3'),
('69', '83', '3'),
('70', '84', '3'),
('71', '85', '3'),
('72', '86', '3'),
('73', '87', '3'),
('74', '88', '3'),
('75', '103', '4'),
('76', '103', '5'),
('77', '117', '6'),
('78', '129', '7'),
('79', '132', '8'),
('80', '138', '9'),
('81', '142', '10'),
('82', '143', '10'),
('83', '144', '10'),
('84', '144', '10'),
('85', '144', '11'),
('86', '175', '12'),
('87', '175', '12'),
('88', '223', '13'),
('89', '300', '14'),
('90', '353', '15'),
('91', '10', '3'),
('92', '10', '3'),
('93', '26', '3');

-- ------------------------------------------------------------

--
-- Table structure for table `carte_supplement`
--

CREATE TABLE `carte_supplement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_carte` int(11) NOT NULL,
  `id_supplement` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_carte_supplement_carte` (`id_carte`),
  KEY `fk_carte_supplement_supplement` (`id_supplement`),
  CONSTRAINT `fk_carte_supplement_carte` FOREIGN KEY (`id_carte`) REFERENCES `carte` (`id`),
  CONSTRAINT `fk_carte_supplement_supplement` FOREIGN KEY (`id_supplement`) REFERENCES `supplements` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `carte_supplement`
--

INSERT INTO `carte_supplement` (`id`, `id_carte`, `id_supplement`) VALUES
('1', '9', '1'),
('2', '9', '2'),
('3', '197', '3'),
('4', '198', '3'),
('5', '199', '3'),
('6', '200', '3'),
('7', '201', '3'),
('8', '202', '3'),
('9', '203', '3'),
('10', '204', '3'),
('11', '205', '3'),
('12', '206', '3'),
('13', '207', '3'),
('14', '208', '3');

-- ------------------------------------------------------------

--
-- Table structure for table `chat_commande`
--

CREATE TABLE `chat_commande` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_commande` int(11) NOT NULL,
  `sender` enum('USER','LIVREUR') NOT NULL,
  `message` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `has_view` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `chat_commande`
--

;

-- ------------------------------------------------------------

--
-- Table structure for table `commande`
--

CREATE TABLE `commande` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `rue` varchar(50) DEFAULT NULL,
  `ville` varchar(50) DEFAULT NULL,
  `code_postal` char(5) DEFAULT NULL,
  `complement` varchar(50) DEFAULT NULL,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `telephone` varchar(32) DEFAULT NULL,
  `id_livreur` int(11) DEFAULT NULL,
  `id_restaurant` int(11) DEFAULT NULL,
  `date_commande` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `heure_souhaite` int(11) NOT NULL DEFAULT '-1',
  `minute_souhaite` int(11) NOT NULL DEFAULT '0',
  `heure_restaurant` int(11) DEFAULT NULL,
  `minute_restaurant` int(11) DEFAULT NULL,
  `prix` double NOT NULL,
  `prix_livraison` double NOT NULL,
  `part_restaurant` int(11) NOT NULL,
  `distance` double DEFAULT NULL,
  `date_validation_restaurant` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_fin_preparation_restaurant` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_recuperation_livreur` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_livraison` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `etape` int(11) NOT NULL,
  `note` int(11) DEFAULT NULL,
  `commentaire` text,
  `is_premium` tinyint(1) DEFAULT '0',
  `last_view_user` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_view_livreur_global` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_view_livreur` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_view_restaurant` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `fk_commande_users` (`uid`),
  KEY `fk_commande_user_livreur` (`id_livreur`),
  KEY `fk_commande_restaurant` (`id_restaurant`),
  CONSTRAINT `fk_commande_restaurant` FOREIGN KEY (`id_restaurant`) REFERENCES `restaurants` (`id`),
  CONSTRAINT `fk_commande_users` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`),
  CONSTRAINT `fk_commande_user_livreur` FOREIGN KEY (`id_livreur`) REFERENCES `users` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `commande`
--

INSERT INTO `commande` (`id`, `uid`, `rue`, `ville`, `code_postal`, `complement`, `latitude`, `longitude`, `telephone`, `id_livreur`, `id_restaurant`, `date_commande`, `heure_souhaite`, `minute_souhaite`, `heure_restaurant`, `minute_restaurant`, `prix`, `prix_livraison`, `part_restaurant`, `distance`, `date_validation_restaurant`, `date_fin_preparation_restaurant`, `date_recuperation_livreur`, `date_livraison`, `etape`, `note`, `commentaire`, `is_premium`, `last_view_user`, `last_view_livreur_global`, `last_view_livreur`, `last_view_restaurant`) VALUES
('1', '4', '22 Rue du Commerce', 'Juziers', '78820', '', '0', '0', '0636601045', '', '1', '2016-05-25 00:37:48', '-1', '0', '', '', '15', '2.5', '0', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0', '', '', '0', '2016-06-02 08:02:37', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
('2', '4', '22 Rue du Commerce', 'Juziers', '78820', '', '0', '0', '0636601045', '3', '5', '2016-06-02 07:59:00', '19', '0', '', '', '17', '2.5', '0', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0', '', '', '0', '2016-06-02 08:02:37', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2016-06-02 12:02:36');

-- ------------------------------------------------------------

--
-- Table structure for table `commande_carte`
--

CREATE TABLE `commande_carte` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_commande` int(11) NOT NULL,
  `id_carte` int(11) NOT NULL,
  `id_format` int(11) NOT NULL,
  `quantite` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_commande_carte_commande` (`id_commande`),
  KEY `fk_commande_carte_carte` (`id_carte`),
  CONSTRAINT `fk_commande_carte_carte` FOREIGN KEY (`id_carte`) REFERENCES `carte` (`id`),
  CONSTRAINT `fk_commande_carte_commande` FOREIGN KEY (`id_commande`) REFERENCES `commande` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `commande_carte`
--

INSERT INTO `commande_carte` (`id`, `id_commande`, `id_carte`, `id_format`, `quantite`) VALUES
('1', '1', '5', '1', '3');

-- ------------------------------------------------------------

--
-- Table structure for table `commande_carte_accompagnement`
--

CREATE TABLE `commande_carte_accompagnement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_commande_carte` int(11) NOT NULL,
  `id_accompagnement` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_commande_carte_accompagnement_commande_carte` (`id_commande_carte`),
  KEY `fk_commande_carte_accompagnement_carte` (`id_accompagnement`),
  CONSTRAINT `fk_commande_carte_accompagnement_carte` FOREIGN KEY (`id_accompagnement`) REFERENCES `carte` (`id`),
  CONSTRAINT `fk_commande_carte_accompagnement_commande_carte` FOREIGN KEY (`id_commande_carte`) REFERENCES `commande_carte` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `commande_carte_accompagnement`
--

;

-- ------------------------------------------------------------

--
-- Table structure for table `commande_carte_accompagnement_history`
--

CREATE TABLE `commande_carte_accompagnement_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_commande_carte` int(11) NOT NULL,
  `id_accompagnement` int(11) NOT NULL,
  `nom_accompagnement` varchar(32) NOT NULL,
  `id_categorie` int(11) NOT NULL,
  `nom_categorie` varchar(30) NOT NULL,
  `parent_categorie` int(11) NOT NULL,
  `parent_nom` varchar(30) NOT NULL,
  `commentaire_accompagnement` text,
  PRIMARY KEY (`id`),
  KEY `fk_ccah_commande_carte_history` (`id_commande_carte`),
  CONSTRAINT `fk_ccah_commande_carte_history` FOREIGN KEY (`id_commande_carte`) REFERENCES `commande_carte_history` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `commande_carte_accompagnement_history`
--

;

-- ------------------------------------------------------------

--
-- Table structure for table `commande_carte_history`
--

CREATE TABLE `commande_carte_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_commande` int(11) NOT NULL,
  `quantite` int(11) NOT NULL,
  `id_carte` int(11) NOT NULL,
  `nom_carte` varchar(32) NOT NULL,
  `id_categorie` int(11) NOT NULL,
  `nom_categorie` varchar(30) NOT NULL,
  `parent_categorie` int(11) DEFAULT NULL,
  `parent_nom` varchar(30) DEFAULT NULL,
  `commentaire_carte` text,
  PRIMARY KEY (`id`),
  KEY `fk_commande_carte_history_commande_history` (`id_commande`),
  CONSTRAINT `fk_commande_carte_history_commande_history` FOREIGN KEY (`id_commande`) REFERENCES `commande_history` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `commande_carte_history`
--

;

-- ------------------------------------------------------------

--
-- Table structure for table `commande_carte_option`
--

CREATE TABLE `commande_carte_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_commande_carte` int(11) NOT NULL,
  `id_option` int(11) NOT NULL,
  `id_value` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_commande_carte_option_commande_carte` (`id_commande_carte`),
  KEY `fk_commande_carte_option_option` (`id_option`),
  KEY `fk_commande_carte_option_value` (`id_value`),
  CONSTRAINT `fk_commande_carte_option_commande_carte` FOREIGN KEY (`id_commande_carte`) REFERENCES `commande_carte` (`id`),
  CONSTRAINT `fk_commande_carte_option_option` FOREIGN KEY (`id_option`) REFERENCES `restaurant_option` (`id`),
  CONSTRAINT `fk_commande_carte_option_value` FOREIGN KEY (`id_value`) REFERENCES `restaurant_option_value` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `commande_carte_option`
--

;

-- ------------------------------------------------------------

--
-- Table structure for table `commande_carte_supplement`
--

CREATE TABLE `commande_carte_supplement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_commande_carte` int(11) NOT NULL,
  `id_supplement` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_commande_carte_supplement_commande_carte` (`id_commande_carte`),
  KEY `fk_commande_carte_supplement_supplement` (`id_supplement`),
  CONSTRAINT `fk_commande_carte_supplement_commande_carte` FOREIGN KEY (`id_commande_carte`) REFERENCES `commande_carte` (`id`),
  CONSTRAINT `fk_commande_carte_supplement_supplement` FOREIGN KEY (`id_supplement`) REFERENCES `supplements` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `commande_carte_supplement`
--

;

-- ------------------------------------------------------------

--
-- Table structure for table `commande_carte_supplement_history`
--

CREATE TABLE `commande_carte_supplement_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_commande_carte` int(11) NOT NULL,
  `id_supplement` int(11) NOT NULL,
  `nom_supplement` varchar(32) NOT NULL,
  `prix_supplement` double NOT NULL,
  `commentaire_supplement` text,
  PRIMARY KEY (`id`),
  KEY `fk_ccsh_commande_carte_history` (`id_commande_carte`),
  CONSTRAINT `fk_ccsh_commande_carte_history` FOREIGN KEY (`id_commande_carte`) REFERENCES `commande_carte_history` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `commande_carte_supplement_history`
--

;

-- ------------------------------------------------------------

--
-- Table structure for table `commande_history`
--

CREATE TABLE `commande_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_commande` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `nom_user` varchar(32) NOT NULL,
  `prenom_user` varchar(32) NOT NULL,
  `email_user` varchar(50) NOT NULL,
  `rue_user` varchar(32) DEFAULT NULL,
  `ville_user` varchar(32) DEFAULT NULL,
  `code_postal_user` char(5) DEFAULT NULL,
  `rue_commande` varchar(32) NOT NULL,
  `ville_commande` varchar(32) NOT NULL,
  `code_postal_commande` char(5) NOT NULL,
  `latitude_commande` double NOT NULL,
  `longitude_commande` double NOT NULL,
  `telephone_commande` varchar(32) DEFAULT NULL,
  `id_livreur` int(11) DEFAULT NULL,
  `nom_livreur` varchar(32) NOT NULL,
  `prenom_livreur` varchar(32) NOT NULL,
  `login_livreur` varchar(50) NOT NULL,
  `id_restaurant` int(11) DEFAULT NULL,
  `nom_restaurant` varchar(32) NOT NULL,
  `rue_restaurant` varchar(32) NOT NULL,
  `ville_restaurant` varchar(32) NOT NULL,
  `code_postal_restaurant` varchar(32) NOT NULL,
  `telephone_restaurant` varchar(32) DEFAULT NULL,
  `latitude_restaurant` double NOT NULL,
  `longitude_restaurant` double NOT NULL,
  `date_commande` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `heure_souhaite` int(11) NOT NULL DEFAULT '-1',
  `minute_souhaite` int(11) NOT NULL DEFAULT '0',
  `prix` double NOT NULL,
  `prix_livraison` double NOT NULL,
  `part_restaurant` int(11) NOT NULL,
  `distance` double DEFAULT NULL,
  `date_validation_restaurant` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_fin_preparation_restaurant` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_recuperation_livreur` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_livraison` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `etape` int(11) NOT NULL,
  `note` int(11) DEFAULT NULL,
  `commentaire` text,
  `date_history` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_commande` (`id_commande`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `commande_history`
--

;

-- ------------------------------------------------------------

--
-- Table structure for table `commande_menu`
--

CREATE TABLE `commande_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_commande` int(11) NOT NULL,
  `id_menu` int(11) NOT NULL,
  `id_format` int(11) NOT NULL,
  `id_formule` int(11) NOT NULL,
  `quantite` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_commande_menu_commande` (`id_commande`),
  KEY `fk_commande_menu_menus` (`id_menu`),
  KEY `fk_commande_menu_format` (`id_format`),
  KEY `fk_commande_menu_formule` (`id_formule`),
  CONSTRAINT `fk_commande_menu_commande` FOREIGN KEY (`id_commande`) REFERENCES `commande` (`id`),
  CONSTRAINT `fk_commande_menu_format` FOREIGN KEY (`id_format`) REFERENCES `restaurant_format` (`id`),
  CONSTRAINT `fk_commande_menu_formule` FOREIGN KEY (`id_formule`) REFERENCES `restaurant_formule` (`id`),
  CONSTRAINT `fk_commande_menu_menus` FOREIGN KEY (`id_menu`) REFERENCES `menus` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `commande_menu`
--

INSERT INTO `commande_menu` (`id`, `id_commande`, `id_menu`, `id_format`, `id_formule`, `quantite`) VALUES
('1', '2', '1', '5', '1', '1');

-- ------------------------------------------------------------

--
-- Table structure for table `commande_menu_accompagnement`
--

CREATE TABLE `commande_menu_accompagnement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_commande_menu_contenu` int(11) NOT NULL,
  `id_accompagnement` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_commande_menu_accompagnement_commande_menu_contenu` (`id_commande_menu_contenu`),
  KEY `fk_commande_menu_accompagnement_accompagnement` (`id_accompagnement`),
  CONSTRAINT `fk_commande_menu_accompagnement_accompagnement` FOREIGN KEY (`id_accompagnement`) REFERENCES `carte` (`id`),
  CONSTRAINT `fk_commande_menu_accompagnement_commande_menu_contenu` FOREIGN KEY (`id_commande_menu_contenu`) REFERENCES `commande_menu_contenu` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `commande_menu_accompagnement`
--

;

-- ------------------------------------------------------------

--
-- Table structure for table `commande_menu_accompagnement_history`
--

CREATE TABLE `commande_menu_accompagnement_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_commande_menu_contenu` int(11) NOT NULL,
  `id_accompagnement` int(11) NOT NULL,
  `nom_accompagnement` varchar(32) NOT NULL,
  `id_categorie` int(11) NOT NULL,
  `nom_categorie` varchar(30) NOT NULL,
  `parent_categorie` int(11) NOT NULL,
  `parent_nom` varchar(30) NOT NULL,
  `commentaire_accompagnement` text,
  PRIMARY KEY (`id`),
  KEY `fk_cmah_cmch` (`id_commande_menu_contenu`),
  CONSTRAINT `fk_cmah_cmch` FOREIGN KEY (`id_commande_menu_contenu`) REFERENCES `commande_menu_contenu_history` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `commande_menu_accompagnement_history`
--

;

-- ------------------------------------------------------------

--
-- Table structure for table `commande_menu_contenu`
--

CREATE TABLE `commande_menu_contenu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_commande_menu` int(11) NOT NULL,
  `id_contenu` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_commande_menu_contenu_commande_menu` (`id_commande_menu`),
  KEY `fk_commande_menu_contenu_menu_contenu` (`id_contenu`),
  CONSTRAINT `fk_commande_menu_contenu_commande_menu` FOREIGN KEY (`id_commande_menu`) REFERENCES `commande_menu` (`id`),
  CONSTRAINT `fk_commande_menu_contenu_menu_contenu` FOREIGN KEY (`id_contenu`) REFERENCES `menu_contenu` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `commande_menu_contenu`
--

INSERT INTO `commande_menu_contenu` (`id`, `id_commande_menu`, `id_contenu`) VALUES
('1', '1', '1'),
('2', '1', '4'),
('3', '1', '8');

-- ------------------------------------------------------------

--
-- Table structure for table `commande_menu_contenu_history`
--

CREATE TABLE `commande_menu_contenu_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_commande_menu` int(11) NOT NULL,
  `id_categorie` int(11) NOT NULL,
  `nom_categorie` varchar(30) NOT NULL,
  `parent_categorie` int(11) NOT NULL,
  `parent_nom` varchar(30) NOT NULL,
  `id_carte` int(11) NOT NULL,
  `nom_carte` varchar(32) NOT NULL,
  `commentaire_carte` text,
  `contenu_obligatoire` tinyint(1) NOT NULL,
  `contenu_limite_supplement` int(11) NOT NULL DEFAULT '-1',
  `contenu_limite_accompagnement` int(11) NOT NULL DEFAULT '0',
  `commentaire_contenu` text,
  PRIMARY KEY (`id`),
  KEY `fk_cmch_cmh` (`id_commande_menu`),
  CONSTRAINT `fk_cmch_cmh` FOREIGN KEY (`id_commande_menu`) REFERENCES `commande_menu_history` (`id_commande`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `commande_menu_contenu_history`
--

;

-- ------------------------------------------------------------

--
-- Table structure for table `commande_menu_history`
--

CREATE TABLE `commande_menu_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_commande` int(11) NOT NULL,
  `id_menu` int(11) NOT NULL,
  `nom_menu` varchar(32) NOT NULL,
  `commentaire_menu` text,
  `id_format` int(11) NOT NULL,
  `nom_format` varchar(32) NOT NULL,
  `quantite` int(11) NOT NULL,
  `prix` double NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_commande_menu_history_commande_history` (`id_commande`),
  CONSTRAINT `fk_commande_menu_history_commande_history` FOREIGN KEY (`id_commande`) REFERENCES `commande_history` (`id_commande`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `commande_menu_history`
--

;

-- ------------------------------------------------------------

--
-- Table structure for table `commande_menu_supplement`
--

CREATE TABLE `commande_menu_supplement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_commande_menu_contenu` int(11) NOT NULL,
  `id_supplement` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_commande_menu_supplement_commande_menu_contenu` (`id_commande_menu_contenu`),
  KEY `fk_commande_menu_supplement_supplement` (`id_supplement`),
  CONSTRAINT `fk_commande_menu_supplement_commande_menu_contenu` FOREIGN KEY (`id_commande_menu_contenu`) REFERENCES `commande_menu_contenu` (`id`),
  CONSTRAINT `fk_commande_menu_supplement_supplement` FOREIGN KEY (`id_supplement`) REFERENCES `supplements` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `commande_menu_supplement`
--

;

-- ------------------------------------------------------------

--
-- Table structure for table `commande_menu_supplement_history`
--

CREATE TABLE `commande_menu_supplement_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_commande_menu_contenu` int(11) NOT NULL,
  `id_supplement` int(11) NOT NULL,
  `nom_supplemnt` varchar(32) NOT NULL,
  `prix_supplemnt` double NOT NULL,
  `commentaire_supplemnt` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_cmsh_cmch` (`id_commande_menu_contenu`),
  CONSTRAINT `fk_cmsh_cmch` FOREIGN KEY (`id_commande_menu_contenu`) REFERENCES `commande_menu_contenu_history` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `commande_menu_supplement_history`
--

;

-- ------------------------------------------------------------

--
-- Table structure for table `days`
--

CREATE TABLE `days` (
  `id` int(11) NOT NULL,
  `nom` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `days`
--

INSERT INTO `days` (`id`, `nom`) VALUES
('1', 'Lundi'),
('2', 'Mardi'),
('3', 'Mercredi'),
('4', 'Jeudi'),
('5', 'Vendredi'),
('6', 'Samedi'),
('7', 'Dimanche');

-- ------------------------------------------------------------

--
-- Table structure for table `distance_livreur_resto`
--

CREATE TABLE `distance_livreur_resto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_restaurant` int(11) NOT NULL,
  `id_dispo` int(11) NOT NULL,
  `perimetre` double NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_distance_livreur_resto_dispo` (`id_dispo`),
  KEY `fk_distance_livreur_resto_restaurant` (`id_restaurant`),
  CONSTRAINT `fk_distance_livreur_resto_dispo` FOREIGN KEY (`id_dispo`) REFERENCES `user_livreur_dispo` (`id`),
  CONSTRAINT `fk_distance_livreur_resto_restaurant` FOREIGN KEY (`id_restaurant`) REFERENCES `restaurants` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `distance_livreur_resto`
--

INSERT INTO `distance_livreur_resto` (`id`, `id_restaurant`, `id_dispo`, `perimetre`) VALUES
('1', '1', '2', '0.304'),
('2', '4', '3', '12'),
('3', '5', '1', '10.996'),
('4', '5', '2', '10.996'),
('5', '5', '3', '10.996'),
('6', '5', '4', '10.996'),
('7', '5', '1', '10.996'),
('8', '5', '2', '10.996'),
('9', '5', '3', '10.996'),
('10', '5', '4', '10.996'),
('11', '5', '5', '10.996'),
('12', '5', '1', '10.996'),
('13', '5', '2', '10.996'),
('14', '5', '3', '10.996'),
('15', '5', '4', '10.996'),
('16', '5', '5', '10.996'),
('17', '5', '1', '10.996'),
('18', '5', '2', '10.996'),
('19', '5', '3', '10.996'),
('20', '5', '4', '10.996'),
('21', '5', '5', '10.996'),
('22', '6', '1', '6.565'),
('23', '6', '2', '6.565'),
('24', '6', '3', '6.565'),
('25', '6', '4', '6.565'),
('26', '6', '5', '6.565'),
('27', '7', '1', '11.05'),
('28', '7', '2', '11.05'),
('29', '7', '3', '11.05'),
('30', '7', '4', '11.05'),
('31', '7', '5', '11.05'),
('32', '8', '1', '11.417'),
('33', '8', '2', '11.417'),
('34', '8', '3', '11.417'),
('35', '8', '4', '11.417'),
('36', '8', '5', '11.417');

-- ------------------------------------------------------------

--
-- Table structure for table `mails`
--

CREATE TABLE `mails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email_from` varchar(100) NOT NULL,
  `email_to` varchar(100) NOT NULL,
  `sujet` varchar(255) NOT NULL,
  `contenu` text NOT NULL,
  `attachements` text,
  `id_user` int(11) DEFAULT NULL,
  `date_envoie` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_send` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `mails`
--

INSERT INTO `mails` (`id`, `email_from`, `email_to`, `sujet`, `contenu`, `attachements`, `id_user`, `date_envoie`, `is_send`) VALUES
('1', 'test@homemenus.fr', 'fiarimike2@yahoo.fr', 'Création de votre compte', '<html>\r\n	<body>\r\n		<div style=\"color:#000; background-color:#fff; font-family:verdana, helvetica, sans-serif;font-size:16px\">\r\n			<div style=\"font-family: verdana, helvetica, sans-serif; font-size: 16px;\">\r\n				<div style=\"font-family: HelveticaNeue, ''Helvetica Neue'', Helvetica, Arial, ''Lucida Grande'', sans-serif; font-size: 12px;\">\r\n					<div class=\"y_msg_container\">\r\n						<div id=\"yiv7745695598\">\r\n							<title>\r\n								homemenus.fr - Création de compte\r\n							</title>\r\n							<style type=\"text/css\">\r\n								#yiv7745695598 a {\r\n									border:none;\r\n								}\r\n								\r\n								#yiv7745695598 img {\r\n									border:none;\r\n								}\r\n								\r\n								#yiv7745695598 p {\r\n									margin:0;line-height:1.3em;\r\n								}\r\n								\r\n								#yiv7745695598 #yiv7745695598footer-msg a {\r\n									color:#F3A836;\r\n								}\r\n								\r\n								#yiv7745695598 h1, #yiv7745695598 h2, #yiv7745695598 h3, #yiv7745695598 h4, #yiv7745695598 h5, #yiv7745695598 h6 {\r\n									font-size:100%;\r\n									margin:0;\r\n								}\r\n							</style>\r\n							<div>\r\n								<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" align=\"center\" width=\"100%\">\r\n									<tbody>\r\n										<tr>\r\n											<td align=\"center\" style=\"padding:37px 0;background-color:#eeeeee;\" bgcolor=\"#eeeeee\">\r\n												<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style=\"margin: 0px; border: 1px solid rgb(221, 221, 221); color: rgb(68, 68, 68); font-family: arial; font-size: 12px; background-color: rgb(255, 255, 255);\" width=\"600\">\r\n													<tbody>\r\n														<tr>\r\n															<td>\r\n																<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"100%\">\r\n																	<tbody>\r\n																		<tr>\r\n																			<td>\r\n																				<img alt=\"\" style=\"display:block;width:400px;height:150px;border:none;margin : 0 auto;\" src=\"http://localhost/projets/homemenus/website/web/res/img/logo_mail.png\">\r\n																			</td>\r\n																		</tr>\r\n																	</tbody>\r\n																</table>\r\n																<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style=\"margin:0;border-collapse:collapse;\" width=\"100%\">\r\n																	<tbody>\r\n																		<tr>\r\n																			<td><h3 style=\"color:#FF0000; font-size: 36px; font-weight: bold; text-align : center;\">ENVIRONNEMENT DE DÉVELOPPEMENT</h3></td>\r\n																		</tr>\r\n																		<tr>\r\n																			<td style=\"color: rgb(68, 68, 68); font-family: arial; font-size: 12px; border-color: rgb(221, 221, 221); padding: 5px 0px; background-color: rgb(255, 255, 255);\" align=\"left\">\r\n																				<table style=\"margin: 0px 0px 0px 10px; border-collapse: collapse; color: rgb(68, 68, 68); font-family: arial; font-size: 12px; border-color: rgb(221, 221, 221); background-color: rgb(255, 255, 255);\">\r\n																					<tbody>\r\n																						<tr>\r\n																							<td width=\"580\" style=\"vertical-align:top;padding:5px 0;\">\r\n																								<table cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: collapse; width: 565px; color: rgb(68, 68, 68); font-family: arial; font-size: 12px; border-color: rgb(221, 221, 221); background-color: rgb(255, 255, 255);\" width=\"565\">\r\n																									<tbody>\r\n																										<tr>\r\n																											<td style=\"padding: 5px 0px 5px 5px; line-height: normal; color:rgb(68, 68, 68); font-family: arial; font-size: 12px; border-color: rgb(221, 221, 221); background-color: rgb(255, 255, 255);\">\r\n																												<div style=\"text-align:center;margin:0 0 10px;line-height:1.3em;\"><span style=\"color:#696969;\"><b><span style=\"font-size:26px;\">Création de votre compte</span></b></span></div>\r\n																											</td>\r\n																										</tr>\r\n																									</tbody>\r\n																								</table>\r\n																							</td>\r\n																						</tr>\r\n																					</tbody>\r\n																				</table>\r\n																				<table style=\"margin: 0px 0px 0px 10px; border-collapse: collapse; color: rgb(68, 68, 68); font-family: arial; font-size: 12px; border-color: rgb(221, 221, 221); background-color: rgb(255, 255, 255);\">\r\n																					<tbody>\r\n																						<tr>\r\n																							<td width=\"580\" style=\"vertical-align:top;padding:5px 0;\">\r\n																								<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"100%\" style=\"color: rgb(68, 68, 68); font-family: arial; font-size: 12px; border-color: rgb(221, 221, 221); background-color: rgb(255, 255, 255);\">\r\n																									<tbody>\r\n																										<tr>\r\n																											<td style=\"vertical-align:top;padding:5px;\">\r\n																												<div style=\"margin:0 0 10px;line-height:1.3em;\"><span style=\"font-size:14px;\"><span style=\"color:#808080;\">\r\n																													<br />\r\n																													Bonjour mike fiari,<br /><br />\r\n                                                            \r\n																													Votre compte a bien été créé.<br />\r\n																													<br /><br />\r\n																													Pour activer votre compte il suffit de cliquer sur le lien ci-dessous afin de confirmer votre adresse mail.<br />\r\n																													<br>\r\n																													<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"230px\" style=\"color: rgb(68, 68, 68); font-family: arial; font-size: 12px; border-color: rgb(221, 221, 221); background-color: rgb(255, 255, 255); margin : 0 auto;\">\r\n																														<tbody>\r\n																															<tr>\r\n																																<td align=\"center\" valign=\"middle\" bgcolor=\"\" style=\"background-repeat:repeat-x;border:1px solid;background-color:#36aebb;border-color:#444444;\">\r\n																																	<div style=\"padding:0;padding-left:5px;padding-right:5px;\">\r\n																																		<table cellpadding=\"12\" cellspacing=\"1\">\r\n																																			<tbody>\r\n																																				<tr>\r\n																																					<td><a rel=\"nofollow\" target=\"_blank\" href=\"http://localhost/projets/homemenus/website/web/index.php?module=default&controler=compte&action=activation&uid=7&token=-0hFiP_DJgjmN80uZSj3uolD4zSp6Abs\" style=\"text-decoration:none;border:none;\"><span style=\"color: rgb(255,255, 255); font-family: arial; font-size: 14px; white-space: nowrap; display: block;\">\r\n																																						Confirmer mon adresse mail           \r\n																																					</span></a></td>\r\n																																				</tr>\r\n																																			</tbody>\r\n																																		</table>\r\n																																	</div>\r\n																																</td>\r\n																															</tr>\r\n																														</tbody>\r\n																													</table>\r\n																												</div>\r\n																											</td>\r\n																										</tr>\r\n																									</tbody>\r\n																								</table>\r\n																							</td>\r\n																						</tr>\r\n																					</tbody>\r\n																				</table>\r\n																			</td>\r\n																		</tr>\r\n																	</tbody>\r\n																</table>\r\n															</td>\r\n														</tr>\r\n													</tbody>\r\n												</table>\r\n											</td>\r\n										</tr>\r\n									</tbody>\r\n								</table>\r\n							</div>\r\n						</div>\r\n					</div>\r\n				</div>\r\n			</div>\r\n		</div>\r\n	</body>\r\n</html>', '', '', '2016-05-20 08:13:27', '0'),
('2', 'test@homemenus.fr', 'fiarimike@yahoo.fr', 'Changement de mot de passe', '<html>\r\n	<body>\r\n		<div style=\"color:#000; background-color:#fff; font-family:verdana, helvetica, sans-serif;font-size:16px\">\r\n			<div style=\"font-family: verdana, helvetica, sans-serif; font-size: 16px;\">\r\n				<div style=\"font-family: HelveticaNeue, ''Helvetica Neue'', Helvetica, Arial, ''Lucida Grande'', sans-serif; font-size: 12px;\">\r\n					<div class=\"y_msg_container\">\r\n						<div id=\"yiv7745695598\">\r\n							<title>\r\n								homemenus.fr - Création de compte\r\n							</title>\r\n							<style type=\"text/css\">\r\n								#yiv7745695598 a {\r\n									border:none;\r\n								}\r\n								\r\n								#yiv7745695598 img {\r\n									border:none;\r\n								}\r\n								\r\n								#yiv7745695598 p {\r\n									margin:0;line-height:1.3em;\r\n								}\r\n								\r\n								#yiv7745695598 #yiv7745695598footer-msg a {\r\n									color:#F3A836;\r\n								}\r\n								\r\n								#yiv7745695598 h1, #yiv7745695598 h2, #yiv7745695598 h3, #yiv7745695598 h4, #yiv7745695598 h5, #yiv7745695598 h6 {\r\n									font-size:100%;\r\n									margin:0;\r\n								}\r\n							</style>\r\n							<div>\r\n								<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" align=\"center\" width=\"100%\">\r\n									<tbody>\r\n										<tr>\r\n											<td align=\"center\" style=\"padding:37px 0;background-color:#eeeeee;\" bgcolor=\"#eeeeee\">\r\n												<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style=\"margin: 0px; border: 1px solid rgb(221, 221, 221); color: rgb(68, 68, 68); font-family: arial; font-size: 12px; background-color: rgb(255, 255, 255);\" width=\"600\">\r\n													<tbody>\r\n														<tr>\r\n															<td>\r\n																<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"100%\">\r\n																	<tbody>\r\n																		<tr>\r\n																			<td>\r\n																				<img alt=\"\" style=\"display:block;width:400px;height:150px;border:none;margin : 0 auto;\" src=\"http://homemenus.fr/web/res/img/logo_mail.png\">\r\n																			</td>\r\n																		</tr>\r\n																	</tbody>\r\n																</table>\r\n																<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style=\"margin:0;border-collapse:collapse;\" width=\"100%\">\r\n																	<tbody>\r\n																		<tr>\r\n																			<td><h3 style=\"color:#FF0000; font-size: 36px; font-weight: bold; text-align : center;\">ENVIRONNEMENT DE DÉVELOPPEMENT</h3></td>\r\n																		</tr>\r\n																		<tr>\r\n																			<td style=\"color: rgb(68, 68, 68); font-family: arial; font-size: 12px; border-color: rgb(221, 221, 221); padding: 5px 0px; background-color: rgb(255, 255, 255);\" align=\"left\">\r\n																				<table style=\"margin: 0px 0px 0px 10px; border-collapse: collapse; color: rgb(68, 68, 68); font-family: arial; font-size: 12px; border-color: rgb(221, 221, 221); background-color: rgb(255, 255, 255);\">\r\n																					<tbody>\r\n																						<tr>\r\n																							<td width=\"580\" style=\"vertical-align:top;padding:5px 0;\">\r\n																								<table cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: collapse; width: 565px; color: rgb(68, 68, 68); font-family: arial; font-size: 12px; border-color: rgb(221, 221, 221); background-color: rgb(255, 255, 255);\" width=\"565\">\r\n																									<tbody>\r\n																										<tr>\r\n																											<td style=\"padding: 5px 0px 5px 5px; line-height: normal; color:rgb(68, 68, 68); font-family: arial; font-size: 12px; border-color: rgb(221, 221, 221); background-color: rgb(255, 255, 255);\">\r\n																												<div style=\"text-align:center;margin:0 0 10px;line-height:1.3em;\"><span style=\"color:#696969;\"><b><span style=\"font-size:26px;\">Modification de votre mot de passe</span></b></span></div>\r\n																											</td>\r\n																										</tr>\r\n																									</tbody>\r\n																								</table>\r\n																							</td>\r\n																						</tr>\r\n																					</tbody>\r\n																				</table>\r\n																				<table style=\"margin: 0px 0px 0px 10px; border-collapse: collapse; color: rgb(68, 68, 68); font-family: arial; font-size: 12px; border-color: rgb(221, 221, 221); background-color: rgb(255, 255, 255);\">\r\n																					<tbody>\r\n																						<tr>\r\n																							<td width=\"580\" style=\"vertical-align:top;padding:5px 0;\">\r\n																								<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"100%\" style=\"color: rgb(68, 68, 68); font-family: arial; font-size: 12px; border-color: rgb(221, 221, 221); background-color: rgb(255, 255, 255);\">\r\n																									<tbody>\r\n																										<tr>\r\n																											<td style=\"vertical-align:top;padding:5px;\">\r\n																												<div style=\"margin:0 0 10px;line-height:1.3em;\"><span style=\"font-size:14px;\"><span style=\"color:#808080;\">\r\n																													<br />\r\n																													Bonjour,<br /><br />\r\n                                                            \r\n																													Votre demande de changement de mot de passe a été prise en compte.<br />\r\n																													<br /><br />\r\n																													Pour modifier votre mot de passe, veuillez cliquer sur le lien ci dessous.<br />\r\n																													<br>\r\n																													<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"230px\" style=\"color: rgb(68, 68, 68); font-family: arial; font-size: 12px; border-color: rgb(221, 221, 221); background-color: rgb(255, 255, 255); margin : 0 auto;\">\r\n																														<tbody>\r\n																															<tr>\r\n																																<td align=\"center\" valign=\"middle\" bgcolor=\"\" style=\"background-repeat:repeat-x;border:1px solid;background-color:#36aebb;border-color:#444444;\">\r\n																																	<div style=\"padding:0;padding-left:5px;padding-right:5px;\">\r\n																																		<table cellpadding=\"12\" cellspacing=\"1\">\r\n																																			<tbody>\r\n																																				<tr>\r\n																																					<td><a rel=\"nofollow\" target=\"_blank\" href=\"http://localhost/projets/homemenus/website/web/index.php?controler=compte&action=reset_password&uid=4&token=b5bqgxIfvVzqRuZLlxLa4oAbtEOpMOqG\" style=\"text-decoration:none;border:none;\"><span style=\"color: rgb(255,255, 255); font-family: arial; font-size: 14px; white-space: nowrap; display: block;\">\r\n																																						Changer mon mot de passe           \r\n																																					</span></a></td>\r\n																																				</tr>\r\n																																			</tbody>\r\n																																		</table>\r\n																																	</div>\r\n																																</td>\r\n																															</tr>\r\n																														</tbody>\r\n																													</table>\r\n																												</div>\r\n																											</td>\r\n																										</tr>\r\n																									</tbody>\r\n																								</table>\r\n																							</td>\r\n																						</tr>\r\n																					</tbody>\r\n																				</table>\r\n																			</td>\r\n																		</tr>\r\n																	</tbody>\r\n																</table>\r\n															</td>\r\n														</tr>\r\n													</tbody>\r\n												</table>\r\n											</td>\r\n										</tr>\r\n									</tbody>\r\n								</table>\r\n							</div>\r\n						</div>\r\n					</div>\r\n				</div>\r\n			</div>\r\n		</div>\r\n	</body>\r\n</html>', '', '', '2016-05-23 07:34:55', '0'),
('3', 'test@homemenus.fr', 'admin@homemenus.fr', 'Nouvelle commande', '<html>\r\n	<body>\r\n		<div style=\"color:#000; background-color:#fff; font-family:verdana, helvetica, sans-serif;font-size:16px\">\r\n			<div style=\"font-family: HelveticaNeue, ''Helvetica Neue'', Helvetica, Arial, ''Lucida Grande'', sans-serif; font-size: 12px;\">\r\n				<div class=\"y_msg_container\">\r\n					<div id=\"yiv7745695598\">\r\n						<title>\r\n							homemenus.fr - Nouvelle commande\r\n						</title>\r\n						<style type=\"text/css\">\r\n							#yiv7745695598 a {\r\n								border:none;\r\n							}\r\n							\r\n							#yiv7745695598 img {\r\n								border:none;\r\n							}\r\n							\r\n							#yiv7745695598 p {\r\n								margin:0;line-height:1.3em;\r\n							}\r\n							\r\n							#yiv7745695598 #yiv7745695598footer-msg a {\r\n								color:#F3A836;\r\n							}\r\n							\r\n							#yiv7745695598 h1, #yiv7745695598 h2, #yiv7745695598 h3, #yiv7745695598 h4, #yiv7745695598 h5, #yiv7745695598 h6 {\r\n								font-size:100%;\r\n								margin:0;\r\n							}\r\n						</style>\r\n						<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" align=\"center\" width=\"100%\">\r\n							<tbody>\r\n								<tr>\r\n									<td align=\"center\" style=\"padding:37px 0;background-color:#eeeeee;\" bgcolor=\"#eeeeee\">\r\n										<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style=\"margin: 0px; border: 1px solid rgb(221, 221, 221); color: rgb(68, 68, 68); font-family: arial; font-size: 12px; background-color: rgb(255, 255, 255);\" width=\"600\">\r\n											<tbody>\r\n												<tr>\r\n													<td>\r\n														<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"100%\">\r\n															<tbody>\r\n																<tr>\r\n																	<td>\r\n																		<img alt=\"\" style=\"display:block;width:400px;height:150px;border:none;margin : 0 auto;\" src=\"http://homemenus.fr/web/res/img/logo_mail.png\">\r\n																	</td>\r\n																</tr>\r\n															</tbody>\r\n														</table>\r\n														<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style=\"margin:0;border-collapse:collapse;\" width=\"100%\">\r\n															<tbody>\r\n																<tr>\r\n																	<td><h3 style=\"color:#FF0000; font-size: 36px; font-weight: bold; text-align : center;\">ENVIRONNEMENT DE DÉVELOPPEMENT</h3></td>\r\n																</tr>\r\n																<tr>\r\n																	<td style=\"color: rgb(68, 68, 68); font-family: arial; font-size: 12px; border-color: rgb(221, 221, 221); padding: 5px 0px; background-color: rgb(255, 255, 255);\" align=\"left\">\r\n																		<table style=\"margin: 0px 0px 0px 10px; border-collapse: collapse; color: rgb(68, 68, 68); font-family: arial; font-size: 12px; border-color: rgb(221, 221, 221); background-color: rgb(255, 255, 255);\">\r\n																			<tbody>\r\n																				<tr>\r\n																					<td width=\"580\" style=\"vertical-align:top;padding:5px 0;\">\r\n																						<table cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: collapse; width: 565px; color: rgb(68, 68, 68); font-family: arial; font-size: 12px; border-color: rgb(221, 221, 221); background-color: rgb(255, 255, 255);\" width=\"565\">\r\n																							<tbody>\r\n																								<tr>\r\n																									<td style=\"padding: 5px 0px 5px 5px; line-height: normal; color:rgb(68, 68, 68); font-family: arial; font-size: 12px; border-color: rgb(221, 221, 221); background-color: rgb(255, 255, 255);\">\r\n																										<div style=\"text-align:center;margin:0 0 10px;line-height:1.3em;\"><span style=\"color:#696969;\"><b><span style=\"font-size:26px;\">Nouvelle commande</span></b></span></div>\r\n																									</td>\r\n																								</tr>\r\n																							</tbody>\r\n																						</table>\r\n																					</td>\r\n																				</tr>\r\n																			</tbody>\r\n																		</table>\r\n																		<table style=\"margin: 0px 0px 0px 10px; border-collapse: collapse; color: rgb(68, 68, 68); font-family: arial; font-size: 12px; border-color: rgb(221, 221, 221); background-color: rgb(255, 255, 255);\">\r\n																			<tbody>\r\n																				<tr>\r\n																					<td width=\"580\" style=\"vertical-align:top;padding:5px 0;\">\r\n																						<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"100%\" style=\"color: rgb(68, 68, 68); font-family: arial; font-size: 12px; border-color: rgb(221, 221, 221); background-color: rgb(255, 255, 255);\">\r\n																							<tbody>\r\n																								<tr>\r\n																									<td style=\"vertical-align:top;padding:5px;\">\r\n																										<div style=\"margin:0 0 10px;line-height:1.3em;\"><span style=\"font-size:14px;\"><span style=\"color:#808080;\">\r\n																											<p>\r\n																												Bonjour. <br /><br />\r\n																												\r\n																												La commande #1 a été payée.<br />\r\n																												Elle est actuellement en cours de traitement.<br /><br />\r\n																												\r\n																											</p><br /><br />\r\n																										</span></span></div>\r\n																									</td>\r\n																								</tr>\r\n																							</tbody>\r\n																						</table>\r\n																					</td>\r\n																				</tr>\r\n																			</tbody>\r\n																		</table>\r\n																	</td>\r\n																</tr>\r\n															</tbody>\r\n														</table>\r\n													</td>\r\n												</tr>\r\n											</tbody>\r\n										</table>\r\n									</td>\r\n								</tr>\r\n							</tbody>\r\n						</table>\r\n					</div>\r\n				</div>\r\n			</div>\r\n		</div>\r\n	</body>\r\n</html>', '', '', '2016-05-25 00:37:49', '0'),
('4', 'test@homemenus.fr', 'admin@homemenus.fr', 'Nouvelle commande', '<html>\r\n	<body>\r\n		<div style=\"color:#000; background-color:#fff; font-family:verdana, helvetica, sans-serif;font-size:16px\">\r\n			<div style=\"font-family: HelveticaNeue, ''Helvetica Neue'', Helvetica, Arial, ''Lucida Grande'', sans-serif; font-size: 12px;\">\r\n				<div class=\"y_msg_container\">\r\n					<div id=\"yiv7745695598\">\r\n						<title>\r\n							homemenus.fr - Nouvelle commande\r\n						</title>\r\n						<style type=\"text/css\">\r\n							#yiv7745695598 a {\r\n								border:none;\r\n							}\r\n							\r\n							#yiv7745695598 img {\r\n								border:none;\r\n							}\r\n							\r\n							#yiv7745695598 p {\r\n								margin:0;line-height:1.3em;\r\n							}\r\n							\r\n							#yiv7745695598 #yiv7745695598footer-msg a {\r\n								color:#F3A836;\r\n							}\r\n							\r\n							#yiv7745695598 h1, #yiv7745695598 h2, #yiv7745695598 h3, #yiv7745695598 h4, #yiv7745695598 h5, #yiv7745695598 h6 {\r\n								font-size:100%;\r\n								margin:0;\r\n							}\r\n						</style>\r\n						<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" align=\"center\" width=\"100%\">\r\n							<tbody>\r\n								<tr>\r\n									<td align=\"center\" style=\"padding:37px 0;background-color:#eeeeee;\" bgcolor=\"#eeeeee\">\r\n										<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style=\"margin: 0px; border: 1px solid rgb(221, 221, 221); color: rgb(68, 68, 68); font-family: arial; font-size: 12px; background-color: rgb(255, 255, 255);\" width=\"600\">\r\n											<tbody>\r\n												<tr>\r\n													<td>\r\n														<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"100%\">\r\n															<tbody>\r\n																<tr>\r\n																	<td>\r\n																		<img alt=\"\" style=\"display:block;width:400px;height:150px;border:none;margin : 0 auto;\" src=\"http://homemenus.fr/web/res/img/logo_mail.png\">\r\n																	</td>\r\n																</tr>\r\n															</tbody>\r\n														</table>\r\n														<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style=\"margin:0;border-collapse:collapse;\" width=\"100%\">\r\n															<tbody>\r\n																<tr>\r\n																	<td><h3 style=\"color:#FF0000; font-size: 36px; font-weight: bold; text-align : center;\">ENVIRONNEMENT DE DÉVELOPPEMENT</h3></td>\r\n																</tr>\r\n																<tr>\r\n																	<td style=\"color: rgb(68, 68, 68); font-family: arial; font-size: 12px; border-color: rgb(221, 221, 221); padding: 5px 0px; background-color: rgb(255, 255, 255);\" align=\"left\">\r\n																		<table style=\"margin: 0px 0px 0px 10px; border-collapse: collapse; color: rgb(68, 68, 68); font-family: arial; font-size: 12px; border-color: rgb(221, 221, 221); background-color: rgb(255, 255, 255);\">\r\n																			<tbody>\r\n																				<tr>\r\n																					<td width=\"580\" style=\"vertical-align:top;padding:5px 0;\">\r\n																						<table cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: collapse; width: 565px; color: rgb(68, 68, 68); font-family: arial; font-size: 12px; border-color: rgb(221, 221, 221); background-color: rgb(255, 255, 255);\" width=\"565\">\r\n																							<tbody>\r\n																								<tr>\r\n																									<td style=\"padding: 5px 0px 5px 5px; line-height: normal; color:rgb(68, 68, 68); font-family: arial; font-size: 12px; border-color: rgb(221, 221, 221); background-color: rgb(255, 255, 255);\">\r\n																										<div style=\"text-align:center;margin:0 0 10px;line-height:1.3em;\"><span style=\"color:#696969;\"><b><span style=\"font-size:26px;\">Nouvelle commande</span></b></span></div>\r\n																									</td>\r\n																								</tr>\r\n																							</tbody>\r\n																						</table>\r\n																					</td>\r\n																				</tr>\r\n																			</tbody>\r\n																		</table>\r\n																		<table style=\"margin: 0px 0px 0px 10px; border-collapse: collapse; color: rgb(68, 68, 68); font-family: arial; font-size: 12px; border-color: rgb(221, 221, 221); background-color: rgb(255, 255, 255);\">\r\n																			<tbody>\r\n																				<tr>\r\n																					<td width=\"580\" style=\"vertical-align:top;padding:5px 0;\">\r\n																						<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"100%\" style=\"color: rgb(68, 68, 68); font-family: arial; font-size: 12px; border-color: rgb(221, 221, 221); background-color: rgb(255, 255, 255);\">\r\n																							<tbody>\r\n																								<tr>\r\n																									<td style=\"vertical-align:top;padding:5px;\">\r\n																										<div style=\"margin:0 0 10px;line-height:1.3em;\"><span style=\"font-size:14px;\"><span style=\"color:#808080;\">\r\n																											<p>\r\n																												Bonjour. <br /><br />\r\n																												\r\n																												La commande #0 a été payée.<br />\r\n																												Elle est actuellement en cours de traitement.<br /><br />\r\n																												\r\n																											</p><br /><br />\r\n																										</span></span></div>\r\n																									</td>\r\n																								</tr>\r\n																							</tbody>\r\n																						</table>\r\n																					</td>\r\n																				</tr>\r\n																			</tbody>\r\n																		</table>\r\n																	</td>\r\n																</tr>\r\n															</tbody>\r\n														</table>\r\n													</td>\r\n												</tr>\r\n											</tbody>\r\n										</table>\r\n									</td>\r\n								</tr>\r\n							</tbody>\r\n						</table>\r\n					</div>\r\n				</div>\r\n			</div>\r\n		</div>\r\n	</body>\r\n</html>', '', '', '2016-05-25 00:37:49', '0'),
('5', 'test@homemenus.fr', 'test@homemenus.fr', 'Nouvelle commande', '<html>\r\n	<body>\r\n		<div style=\"color:#000; background-color:#fff; font-family:verdana, helvetica, sans-serif;font-size:16px\">\r\n			<div style=\"font-family: HelveticaNeue, ''Helvetica Neue'', Helvetica, Arial, ''Lucida Grande'', sans-serif; font-size: 12px;\">\r\n				<div class=\"y_msg_container\">\r\n					<div id=\"yiv7745695598\">\r\n						<title>\r\n							homemenus.fr - Nouvelle commande\r\n						</title>\r\n						<style type=\"text/css\">\r\n							#yiv7745695598 a {\r\n								border:none;\r\n							}\r\n							\r\n							#yiv7745695598 img {\r\n								border:none;\r\n							}\r\n							\r\n							#yiv7745695598 p {\r\n								margin:0;line-height:1.3em;\r\n							}\r\n							\r\n							#yiv7745695598 #yiv7745695598footer-msg a {\r\n								color:#F3A836;\r\n							}\r\n							\r\n							#yiv7745695598 h1, #yiv7745695598 h2, #yiv7745695598 h3, #yiv7745695598 h4, #yiv7745695598 h5, #yiv7745695598 h6 {\r\n								font-size:100%;\r\n								margin:0;\r\n							}\r\n						</style>\r\n						<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" align=\"center\" width=\"100%\">\r\n							<tbody>\r\n								<tr>\r\n									<td align=\"center\" style=\"padding:37px 0;background-color:#eeeeee;\" bgcolor=\"#eeeeee\">\r\n										<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style=\"margin: 0px; border: 1px solid rgb(221, 221, 221); color: rgb(68, 68, 68); font-family: arial; font-size: 12px; background-color: rgb(255, 255, 255);\" width=\"600\">\r\n											<tbody>\r\n												<tr>\r\n													<td>\r\n														<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"100%\">\r\n															<tbody>\r\n																<tr>\r\n																	<td>\r\n																		<img alt=\"\" style=\"display:block;width:400px;height:150px;border:none;margin : 0 auto;\" src=\"http://homemenus.fr/web/res/img/logo_mail.png\">\r\n																	</td>\r\n																</tr>\r\n															</tbody>\r\n														</table>\r\n														<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style=\"margin:0;border-collapse:collapse;\" width=\"100%\">\r\n															<tbody>\r\n																<tr>\r\n																	<td><h3 style=\"color:#FF0000; font-size: 36px; font-weight: bold; text-align : center;\">ENVIRONNEMENT DE DÉVELOPPEMENT</h3></td>\r\n																</tr>\r\n																<tr>\r\n																	<td style=\"color: rgb(68, 68, 68); font-family: arial; font-size: 12px; border-color: rgb(221, 221, 221); padding: 5px 0px; background-color: rgb(255, 255, 255);\" align=\"left\">\r\n																		<table style=\"margin: 0px 0px 0px 10px; border-collapse: collapse; color: rgb(68, 68, 68); font-family: arial; font-size: 12px; border-color: rgb(221, 221, 221); background-color: rgb(255, 255, 255);\">\r\n																			<tbody>\r\n																				<tr>\r\n																					<td width=\"580\" style=\"vertical-align:top;padding:5px 0;\">\r\n																						<table cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: collapse; width: 565px; color: rgb(68, 68, 68); font-family: arial; font-size: 12px; border-color: rgb(221, 221, 221); background-color: rgb(255, 255, 255);\" width=\"565\">\r\n																							<tbody>\r\n																								<tr>\r\n																									<td style=\"padding: 5px 0px 5px 5px; line-height: normal; color:rgb(68, 68, 68); font-family: arial; font-size: 12px; border-color: rgb(221, 221, 221); background-color: rgb(255, 255, 255);\">\r\n																										<div style=\"text-align:center;margin:0 0 10px;line-height:1.3em;\"><span style=\"color:#696969;\"><b><span style=\"font-size:26px;\">Nouvelle commande</span></b></span></div>\r\n																									</td>\r\n																								</tr>\r\n																							</tbody>\r\n																						</table>\r\n																					</td>\r\n																				</tr>\r\n																			</tbody>\r\n																		</table>\r\n																		<table style=\"margin: 0px 0px 0px 10px; border-collapse: collapse; color: rgb(68, 68, 68); font-family: arial; font-size: 12px; border-color: rgb(221, 221, 221); background-color: rgb(255, 255, 255);\">\r\n																			<tbody>\r\n																				<tr>\r\n																					<td width=\"580\" style=\"vertical-align:top;padding:5px 0;\">\r\n																						<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"100%\" style=\"color: rgb(68, 68, 68); font-family: arial; font-size: 12px; border-color: rgb(221, 221, 221); background-color: rgb(255, 255, 255);\">\r\n																							<tbody>\r\n																								<tr>\r\n																									<td style=\"vertical-align:top;padding:5px;\">\r\n																										<div style=\"margin:0 0 10px;line-height:1.3em;\"><span style=\"font-size:14px;\"><span style=\"color:#808080;\">\r\n																											<p>\r\n																												Bonjour. <br /><br />\r\n																												\r\n																												La commande #2 a été payée.<br />\r\n																												Elle est actuellement en cours de traitement.<br /><br />\r\n																												\r\n																											</p><br /><br />\r\n																										</span></span></div>\r\n																									</td>\r\n																								</tr>\r\n																							</tbody>\r\n																						</table>\r\n																					</td>\r\n																				</tr>\r\n																			</tbody>\r\n																		</table>\r\n																	</td>\r\n																</tr>\r\n															</tbody>\r\n														</table>\r\n													</td>\r\n												</tr>\r\n											</tbody>\r\n										</table>\r\n									</td>\r\n								</tr>\r\n							</tbody>\r\n						</table>\r\n					</div>\r\n				</div>\r\n			</div>\r\n		</div>\r\n	</body>\r\n</html>', '', '', '2016-06-02 07:59:01', '0'),
('6', 'test@homemenus.fr', 'tajmahal@yahoo.fr', 'Création de votre compte restaurateur', '<html>\r\n	<body>\r\n		<div style=\"color:#000; background-color:#fff; font-family:verdana, helvetica, sans-serif;font-size:16px\">\r\n			<div style=\"font-family: verdana, helvetica, sans-serif; font-size: 16px;\">\r\n				<div style=\"font-family: HelveticaNeue, ''Helvetica Neue'', Helvetica, Arial, ''Lucida Grande'', sans-serif; font-size: 12px;\">\r\n					<div class=\"y_msg_container\">\r\n						<div id=\"yiv7745695598\">\r\n							<title>\r\n								homemenus.fr - Création de compte restaurant\r\n							</title>\r\n							<style type=\"text/css\">\r\n								#yiv7745695598 a {\r\n									border:none;\r\n								}\r\n								\r\n								#yiv7745695598 img {\r\n									border:none;\r\n								}\r\n								\r\n								#yiv7745695598 p {\r\n									margin:0;line-height:1.3em;\r\n								}\r\n								\r\n								#yiv7745695598 #yiv7745695598footer-msg a {\r\n									color:#F3A836;\r\n								}\r\n								\r\n								#yiv7745695598 h1, #yiv7745695598 h2, #yiv7745695598 h3, #yiv7745695598 h4, #yiv7745695598 h5, #yiv7745695598 h6 {\r\n									font-size:100%;\r\n									margin:0;\r\n								}\r\n							</style>\r\n							<div>\r\n								<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" align=\"center\" width=\"100%\">\r\n									<tbody>\r\n										<tr>\r\n											<td align=\"center\" style=\"padding:37px 0;background-color:#eeeeee;\" bgcolor=\"#eeeeee\">\r\n												<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style=\"margin: 0px; border: 1px solid rgb(221, 221, 221); color: rgb(68, 68, 68); font-family: arial; font-size: 12px; background-color: rgb(255, 255, 255);\" width=\"600\">\r\n													<tbody>\r\n														<tr>\r\n															<td>\r\n																<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"100%\">\r\n																	<tbody>\r\n																		<tr>\r\n																			<td>\r\n																				<img alt=\"\" style=\"display:block;width:400px;height:150px;border:none;margin : 0 auto;\" src=\"http://localhost/projets/homemenus/website/web/res/img/logo_mail.png\">\r\n																			</td>\r\n																		</tr>\r\n																	</tbody>\r\n																</table>\r\n																<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style=\"margin:0;border-collapse:collapse;\" width=\"100%\">\r\n																	<tbody>\r\n																		<tr>\r\n																			<td><h3 style=\"color:#FF0000; font-size: 36px; font-weight: bold; text-align : center;\">ENVIRONNEMENT DE DÉVELOPPEMENT</h3></td>\r\n																		</tr>\r\n																		<tr>\r\n																			<td style=\"color: rgb(68, 68, 68); font-family: arial; font-size: 12px; border-color: rgb(221, 221, 221); padding: 5px 0px; background-color: rgb(255, 255, 255);\" align=\"left\">\r\n																				<table style=\"margin: 0px 0px 0px 10px; border-collapse: collapse; color: rgb(68, 68, 68); font-family: arial; font-size: 12px; border-color: rgb(221, 221, 221); background-color: rgb(255, 255, 255);\">\r\n																					<tbody>\r\n																						<tr>\r\n																							<td width=\"580\" style=\"vertical-align:top;padding:5px 0;\">\r\n																								<table cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: collapse; width: 565px; color: rgb(68, 68, 68); font-family: arial; font-size: 12px; border-color: rgb(221, 221, 221); background-color: rgb(255, 255, 255);\" width=\"565\">\r\n																									<tbody>\r\n																										<tr>\r\n																											<td style=\"padding: 5px 0px 5px 5px; line-height: normal; color:rgb(68, 68, 68); font-family: arial; font-size: 12px; border-color: rgb(221, 221, 221); background-color: rgb(255, 255, 255);\">\r\n																												<div style=\"text-align:center;margin:0 0 10px;line-height:1.3em;\"><span style=\"color:#696969;\"><b><span style=\"font-size:26px;\">Création de votre compte restaurateur</span></b></span></div>\r\n																											</td>\r\n																										</tr>\r\n																									</tbody>\r\n																								</table>\r\n																							</td>\r\n																						</tr>\r\n																					</tbody>\r\n																				</table>\r\n																				<table style=\"margin: 0px 0px 0px 10px; border-collapse: collapse; color: rgb(68, 68, 68); font-family: arial; font-size: 12px; border-color: rgb(221, 221, 221); background-color: rgb(255, 255, 255);\">\r\n																					<tbody>\r\n																						<tr>\r\n																							<td width=\"580\" style=\"vertical-align:top;padding:5px 0;\">\r\n																								<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"100%\" style=\"color: rgb(68, 68, 68); font-family: arial; font-size: 12px; border-color: rgb(221, 221, 221); background-color: rgb(255, 255, 255);\">\r\n																									<tbody>\r\n																										<tr>\r\n																											<td style=\"vertical-align:top;padding:5px;\">\r\n																												<div style=\"margin:0 0 10px;line-height:1.3em;\"><span style=\"font-size:14px;\"><span style=\"color:#808080;\">\r\n																													<br />\r\n																													Bonjour user user,<br /><br />\r\n                                                            \r\n																													Votre compte utilisateur du restaurant Taj Mahal a bien été créé.<br />\r\n																													<br />\r\n																													Vos identifiants permettant de vous connecter sont les suivants : <br />\r\n																													<ul>\r\n																														<li>identifiant : tajmahal_user</li>\r\n																														<li>mot de passe : f144hPta</li>\r\n																													</ul><br /><br />\r\n																													Nous vous invitons à changer de mot de passe après l''activation de votre compte.\r\n																													<br /><br />\r\n																													Pour activer votre compte il suffit de cliquer sur le lien ci-dessous afin de confirmer votre adresse mail.<br />\r\n																													<br>\r\n																													<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"230px\" style=\"color: rgb(68, 68, 68); font-family: arial; font-size: 12px; border-color: rgb(221, 221, 221); background-color: rgb(255, 255, 255); margin : 0 auto;\">\r\n																														<tbody>\r\n																															<tr>\r\n																																<td align=\"center\" valign=\"middle\" bgcolor=\"\" style=\"background-repeat:repeat-x;border:1px solid;background-color:#36aebb;border-color:#444444;\">\r\n																																	<div style=\"padding:0;padding-left:5px;padding-right:5px;\">\r\n																																		<table cellpadding=\"12\" cellspacing=\"1\">\r\n																																			<tbody>\r\n																																				<tr>\r\n																																					<td><a rel=\"nofollow\" target=\"_blank\" href=\"http://localhost/projets/homemenus/website/web/index.php?module=default&controler=compte&action=activation&uid=8&token=VuuQk5bYjgv-84LbB3nG-znaYjJEndkE\" style=\"text-decoration:none;border:none;\"><span style=\"color: rgb(255,255, 255); font-family: arial; font-size: 14px; white-space: nowrap; display: block;\">\r\n																																						Confirmer mon adresse mail           \r\n																																					</span></a></td>\r\n																																				</tr>\r\n																																			</tbody>\r\n																																		</table>\r\n																																	</div>\r\n																																</td>\r\n																															</tr>\r\n																														</tbody>\r\n																													</table>\r\n																												</div>\r\n																											</td>\r\n																										</tr>\r\n																									</tbody>\r\n																								</table>\r\n																							</td>\r\n																						</tr>\r\n																					</tbody>\r\n																				</table>\r\n																			</td>\r\n																		</tr>\r\n																	</tbody>\r\n																</table>\r\n															</td>\r\n														</tr>\r\n													</tbody>\r\n												</table>\r\n											</td>\r\n										</tr>\r\n									</tbody>\r\n								</table>\r\n							</div>\r\n						</div>\r\n					</div>\r\n				</div>\r\n			</div>\r\n		</div>\r\n	</body>\r\n</html>', '', '', '2016-06-02 08:22:50', '0'),
('7', 'test@homemenus.fr', 'tajmahal@yahoo.fr', 'Création de votre compte restaurateur', '<html>\r\n	<body>\r\n		<div style=\"color:#000; background-color:#fff; font-family:verdana, helvetica, sans-serif;font-size:16px\">\r\n			<div style=\"font-family: verdana, helvetica, sans-serif; font-size: 16px;\">\r\n				<div style=\"font-family: HelveticaNeue, ''Helvetica Neue'', Helvetica, Arial, ''Lucida Grande'', sans-serif; font-size: 12px;\">\r\n					<div class=\"y_msg_container\">\r\n						<div id=\"yiv7745695598\">\r\n							<title>\r\n								homemenus.fr - Création de compte restaurant\r\n							</title>\r\n							<style type=\"text/css\">\r\n								#yiv7745695598 a {\r\n									border:none;\r\n								}\r\n								\r\n								#yiv7745695598 img {\r\n									border:none;\r\n								}\r\n								\r\n								#yiv7745695598 p {\r\n									margin:0;line-height:1.3em;\r\n								}\r\n								\r\n								#yiv7745695598 #yiv7745695598footer-msg a {\r\n									color:#F3A836;\r\n								}\r\n								\r\n								#yiv7745695598 h1, #yiv7745695598 h2, #yiv7745695598 h3, #yiv7745695598 h4, #yiv7745695598 h5, #yiv7745695598 h6 {\r\n									font-size:100%;\r\n									margin:0;\r\n								}\r\n							</style>\r\n							<div>\r\n								<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" align=\"center\" width=\"100%\">\r\n									<tbody>\r\n										<tr>\r\n											<td align=\"center\" style=\"padding:37px 0;background-color:#eeeeee;\" bgcolor=\"#eeeeee\">\r\n												<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style=\"margin: 0px; border: 1px solid rgb(221, 221, 221); color: rgb(68, 68, 68); font-family: arial; font-size: 12px; background-color: rgb(255, 255, 255);\" width=\"600\">\r\n													<tbody>\r\n														<tr>\r\n															<td>\r\n																<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"100%\">\r\n																	<tbody>\r\n																		<tr>\r\n																			<td>\r\n																				<img alt=\"\" style=\"display:block;width:400px;height:150px;border:none;margin : 0 auto;\" src=\"http://localhost/projets/homemenus/website/web/res/img/logo_mail.png\">\r\n																			</td>\r\n																		</tr>\r\n																	</tbody>\r\n																</table>\r\n																<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style=\"margin:0;border-collapse:collapse;\" width=\"100%\">\r\n																	<tbody>\r\n																		<tr>\r\n																			<td><h3 style=\"color:#FF0000; font-size: 36px; font-weight: bold; text-align : center;\">ENVIRONNEMENT DE DÉVELOPPEMENT</h3></td>\r\n																		</tr>\r\n																		<tr>\r\n																			<td style=\"color: rgb(68, 68, 68); font-family: arial; font-size: 12px; border-color: rgb(221, 221, 221); padding: 5px 0px; background-color: rgb(255, 255, 255);\" align=\"left\">\r\n																				<table style=\"margin: 0px 0px 0px 10px; border-collapse: collapse; color: rgb(68, 68, 68); font-family: arial; font-size: 12px; border-color: rgb(221, 221, 221); background-color: rgb(255, 255, 255);\">\r\n																					<tbody>\r\n																						<tr>\r\n																							<td width=\"580\" style=\"vertical-align:top;padding:5px 0;\">\r\n																								<table cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: collapse; width: 565px; color: rgb(68, 68, 68); font-family: arial; font-size: 12px; border-color: rgb(221, 221, 221); background-color: rgb(255, 255, 255);\" width=\"565\">\r\n																									<tbody>\r\n																										<tr>\r\n																											<td style=\"padding: 5px 0px 5px 5px; line-height: normal; color:rgb(68, 68, 68); font-family: arial; font-size: 12px; border-color: rgb(221, 221, 221); background-color: rgb(255, 255, 255);\">\r\n																												<div style=\"text-align:center;margin:0 0 10px;line-height:1.3em;\"><span style=\"color:#696969;\"><b><span style=\"font-size:26px;\">Création de votre compte restaurateur</span></b></span></div>\r\n																											</td>\r\n																										</tr>\r\n																									</tbody>\r\n																								</table>\r\n																							</td>\r\n																						</tr>\r\n																					</tbody>\r\n																				</table>\r\n																				<table style=\"margin: 0px 0px 0px 10px; border-collapse: collapse; color: rgb(68, 68, 68); font-family: arial; font-size: 12px; border-color: rgb(221, 221, 221); background-color: rgb(255, 255, 255);\">\r\n																					<tbody>\r\n																						<tr>\r\n																							<td width=\"580\" style=\"vertical-align:top;padding:5px 0;\">\r\n																								<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"100%\" style=\"color: rgb(68, 68, 68); font-family: arial; font-size: 12px; border-color: rgb(221, 221, 221); background-color: rgb(255, 255, 255);\">\r\n																									<tbody>\r\n																										<tr>\r\n																											<td style=\"vertical-align:top;padding:5px;\">\r\n																												<div style=\"margin:0 0 10px;line-height:1.3em;\"><span style=\"font-size:14px;\"><span style=\"color:#808080;\">\r\n																													<br />\r\n																													Bonjour admin admin,<br /><br />\r\n                                                            \r\n																													Votre compte administrateur du restaurant Taj Mahal a bien été créé.<br />\r\n																													<br />\r\n																													Vos identifiants permettant de vous connecter sont les suivants : <br />\r\n																													<ul>\r\n																														<li>identifiant : tajmahal_admin</li>\r\n																														<li>mot de passe : $#LSNnFQ</li>\r\n																													</ul><br /><br />\r\n																													Nous vous invitons à changer de mot de passe après l''activation de votre compte.\r\n																													<br /><br />\r\n																													Pour activer votre compte il suffit de cliquer sur le lien ci-dessous afin de confirmer votre adresse mail.<br />\r\n																													<br>\r\n																													<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"230px\" style=\"color: rgb(68, 68, 68); font-family: arial; font-size: 12px; border-color: rgb(221, 221, 221); background-color: rgb(255, 255, 255); margin : 0 auto;\">\r\n																														<tbody>\r\n																															<tr>\r\n																																<td align=\"center\" valign=\"middle\" bgcolor=\"\" style=\"background-repeat:repeat-x;border:1px solid;background-color:#36aebb;border-color:#444444;\">\r\n																																	<div style=\"padding:0;padding-left:5px;padding-right:5px;\">\r\n																																		<table cellpadding=\"12\" cellspacing=\"1\">\r\n																																			<tbody>\r\n																																				<tr>\r\n																																					<td><a rel=\"nofollow\" target=\"_blank\" href=\"http://localhost/projets/homemenus/website/web/index.php?module=default&controler=compte&action=activation&uid=9&token=FyEi6Sqb712ay4GluQTjdP9_PsGurCIn\" style=\"text-decoration:none;border:none;\"><span style=\"color: rgb(255,255, 255); font-family: arial; font-size: 14px; white-space: nowrap; display: block;\">\r\n																																						Confirmer mon adresse mail           \r\n																																					</span></a></td>\r\n																																				</tr>\r\n																																			</tbody>\r\n																																		</table>\r\n																																	</div>\r\n																																</td>\r\n																															</tr>\r\n																														</tbody>\r\n																													</table>\r\n																												</div>\r\n																											</td>\r\n																										</tr>\r\n																									</tbody>\r\n																								</table>\r\n																							</td>\r\n																						</tr>\r\n																					</tbody>\r\n																				</table>\r\n																			</td>\r\n																		</tr>\r\n																	</tbody>\r\n																</table>\r\n															</td>\r\n														</tr>\r\n													</tbody>\r\n												</table>\r\n											</td>\r\n										</tr>\r\n									</tbody>\r\n								</table>\r\n							</div>\r\n						</div>\r\n					</div>\r\n				</div>\r\n			</div>\r\n		</div>\r\n	</body>\r\n</html>', '', '', '2016-06-02 09:02:35', '0');

-- ------------------------------------------------------------

--
-- Table structure for table `menu_categorie`
--

CREATE TABLE `menu_categorie` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_menu` int(11) NOT NULL,
  `id_formule` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `quantite` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_menu_categorie_menus` (`id_menu`),
  KEY `fk_menu_categorie_formule` (`id_formule`),
  CONSTRAINT `fk_menu_categorie_formule` FOREIGN KEY (`id_formule`) REFERENCES `restaurant_formule` (`id`),
  CONSTRAINT `fk_menu_categorie_menus` FOREIGN KEY (`id_menu`) REFERENCES `menus` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `menu_categorie`
--

INSERT INTO `menu_categorie` (`id`, `id_menu`, `id_formule`, `nom`, `quantite`) VALUES
('1', '1', '1', 'Entrées au choix (avec cheese nan)', '1'),
('2', '1', '1', 'Plats au choix', '1'),
('3', '1', '1', 'Desserts au choix', '1'),
('4', '2', '2', 'Entrées au choix (avec cheese nan ou nan nature)', '1'),
('5', '2', '2', 'Nan au choix', '2'),
('6', '2', '2', 'Plats au choix', '2'),
('7', '2', '2', 'Desserts au choix', '1'),
('8', '2', '2', 'Vins', '1'),
('9', '3', '3', 'Nan', '1'),
('10', '3', '3', 'Plats', '1'),
('11', '3', '3', 'Dessert', '1'),
('12', '4', '4', '1er plat', '1'),
('13', '4', '4', '2ème plat', '1');

-- ------------------------------------------------------------

--
-- Table structure for table `menu_contenu`
--

CREATE TABLE `menu_contenu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_menu_categorie` int(11) NOT NULL,
  `id_carte` int(11) NOT NULL,
  `obligatoire` tinyint(1) NOT NULL,
  `limite_supplement` int(11) NOT NULL DEFAULT '-1',
  `limite_accompagnement` int(11) NOT NULL DEFAULT '0',
  `commentaire` text,
  PRIMARY KEY (`id`),
  KEY `fk_menu_contenu_menu_categorie` (`id_menu_categorie`),
  KEY `fk_menu_contenu_carte` (`id_carte`),
  CONSTRAINT `fk_menu_contenu_carte` FOREIGN KEY (`id_carte`) REFERENCES `carte` (`id`),
  CONSTRAINT `fk_menu_contenu_menu_categorie` FOREIGN KEY (`id_menu_categorie`) REFERENCES `menu_categorie` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `menu_contenu`
--

INSERT INTO `menu_contenu` (`id`, `id_menu_categorie`, `id_carte`, `obligatoire`, `limite_supplement`, `limite_accompagnement`, `commentaire`) VALUES
('1', '1', '10', '0', '0', '0', ''),
('2', '1', '16', '0', '0', '0', ''),
('3', '1', '14', '0', '0', '0', ''),
('4', '2', '50', '0', '0', '0', ''),
('5', '2', '88', '0', '0', '0', ''),
('6', '2', '53', '0', '0', '0', ''),
('7', '3', '92', '0', '0', '0', ''),
('8', '3', '103', '0', '0', '0', ''),
('9', '4', '27', '0', '0', '0', ''),
('10', '4', '29', '0', '0', '0', ''),
('11', '5', '31', '0', '0', '0', ''),
('12', '5', '32', '0', '0', '0', ''),
('13', '6', '47', '0', '0', '0', ''),
('14', '6', '60', '0', '0', '0', ''),
('15', '6', '42', '0', '0', '0', ''),
('16', '6', '72', '0', '0', '0', ''),
('17', '7', '92', '0', '0', '0', ''),
('18', '8', '105', '0', '0', '0', ''),
('19', '8', '104', '0', '0', '0', ''),
('20', '9', '31', '0', '0', '0', ''),
('21', '9', '32', '0', '0', '0', ''),
('22', '11', '92', '0', '0', '0', ''),
('23', '11', '93', '0', '0', '0', ''),
('24', '12', '145', '0', '0', '0', ''),
('25', '12', '146', '0', '0', '0', ''),
('26', '12', '147', '0', '0', '0', ''),
('27', '12', '148', '0', '0', '0', ''),
('28', '12', '149', '0', '0', '0', ''),
('29', '12', '150', '0', '0', '0', ''),
('30', '12', '151', '0', '0', '0', ''),
('31', '12', '152', '0', '0', '0', ''),
('32', '12', '153', '0', '0', '0', ''),
('33', '12', '154', '0', '0', '0', ''),
('34', '12', '155', '0', '0', '0', ''),
('35', '12', '156', '0', '0', '0', ''),
('36', '12', '157', '0', '0', '0', ''),
('37', '12', '158', '0', '0', '0', ''),
('38', '12', '159', '0', '0', '0', ''),
('39', '12', '160', '0', '0', '0', ''),
('40', '12', '161', '0', '0', '0', ''),
('41', '12', '162', '0', '0', '0', ''),
('42', '12', '163', '0', '0', '0', ''),
('43', '12', '164', '0', '0', '0', ''),
('44', '12', '165', '0', '0', '0', ''),
('45', '13', '145', '0', '0', '0', ''),
('46', '13', '146', '0', '0', '0', ''),
('47', '13', '147', '0', '0', '0', ''),
('48', '13', '148', '0', '0', '0', ''),
('49', '13', '149', '0', '0', '0', ''),
('50', '13', '150', '0', '0', '0', ''),
('51', '13', '151', '0', '0', '0', ''),
('52', '13', '152', '0', '0', '0', ''),
('53', '13', '153', '0', '0', '0', ''),
('54', '13', '154', '0', '0', '0', ''),
('55', '13', '155', '0', '0', '0', ''),
('56', '13', '156', '0', '0', '0', ''),
('57', '13', '157', '0', '0', '0', ''),
('58', '13', '158', '0', '0', '0', ''),
('59', '13', '159', '0', '0', '0', ''),
('60', '13', '160', '0', '0', '0', ''),
('61', '13', '161', '0', '0', '0', ''),
('62', '13', '162', '0', '0', '0', ''),
('63', '13', '163', '0', '0', '0', ''),
('64', '13', '164', '0', '0', '0', ''),
('65', '13', '165', '0', '0', '0', '');

-- ------------------------------------------------------------

--
-- Table structure for table `menu_disponibilite`
--

CREATE TABLE `menu_disponibilite` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_menu` int(11) NOT NULL,
  `id_horaire` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_menu_disponibilite_menus` (`id_menu`),
  KEY `fk_menu_disponibilite_horaire` (`id_horaire`),
  CONSTRAINT `fk_menu_disponibilite_horaire` FOREIGN KEY (`id_horaire`) REFERENCES `restaurant_horaires` (`id`),
  CONSTRAINT `fk_menu_disponibilite_menus` FOREIGN KEY (`id_menu`) REFERENCES `menus` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `menu_disponibilite`
--

INSERT INTO `menu_disponibilite` (`id`, `id_menu`, `id_horaire`) VALUES
('1', '1', '28'),
('2', '1', '29'),
('3', '1', '30'),
('4', '1', '31'),
('5', '1', '32'),
('6', '1', '33'),
('7', '1', '34'),
('8', '2', '28'),
('9', '2', '29'),
('10', '2', '30'),
('11', '2', '31'),
('12', '2', '32'),
('13', '2', '33'),
('14', '2', '34'),
('15', '3', '28'),
('16', '3', '29'),
('17', '3', '30'),
('18', '3', '31'),
('19', '3', '32'),
('20', '3', '33'),
('21', '3', '34'),
('22', '4', '35'),
('23', '4', '36'),
('24', '4', '37'),
('25', '4', '38'),
('26', '4', '39'),
('27', '4', '40'),
('28', '4', '41');

-- ------------------------------------------------------------

--
-- Table structure for table `menu_format`
--

CREATE TABLE `menu_format` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_menu` int(11) NOT NULL,
  `id_format` int(11) NOT NULL,
  `prix` double NOT NULL,
  `temps_preparation` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_menu_format_carte` (`id_menu`),
  KEY `fk_menu_format_format` (`id_format`),
  CONSTRAINT `fk_menu_format_carte` FOREIGN KEY (`id_menu`) REFERENCES `menus` (`id`),
  CONSTRAINT `fk_menu_format_format` FOREIGN KEY (`id_format`) REFERENCES `restaurant_format` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `menu_format`
--

INSERT INTO `menu_format` (`id`, `id_menu`, `id_format`, `prix`, `temps_preparation`) VALUES
('1', '1', '5', '17', '10'),
('2', '2', '5', '59', '30'),
('3', '3', '5', '16', '20'),
('4', '4', '15', '14.5', '20');

-- ------------------------------------------------------------

--
-- Table structure for table `menu_formule`
--

CREATE TABLE `menu_formule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_menu` int(11) NOT NULL,
  `id_formule` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_menu_formule_menus` (`id_menu`),
  KEY `fk_menu_formule_formule` (`id_formule`),
  CONSTRAINT `fk_menu_formule_formule` FOREIGN KEY (`id_formule`) REFERENCES `restaurant_formule` (`id`),
  CONSTRAINT `fk_menu_formule_menus` FOREIGN KEY (`id_menu`) REFERENCES `menus` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `menu_formule`
--

INSERT INTO `menu_formule` (`id`, `id_menu`, `id_formule`) VALUES
('1', '1', '1'),
('2', '2', '2'),
('3', '3', '3'),
('4', '4', '4');

-- ------------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  `id_restaurant` int(11) NOT NULL,
  `ordre` int(11) NOT NULL,
  `commentaire` text,
  PRIMARY KEY (`id`),
  KEY `fk_menus_restaurants` (`id_restaurant`),
  CONSTRAINT `fk_menus_restaurants` FOREIGN KEY (`id_restaurant`) REFERENCES `restaurants` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`id`, `nom`, `id_restaurant`, `ordre`, `commentaire`) VALUES
('1', 'Menu', '5', '0', ''),
('2', 'Menu maharaja', '5', '0', 'Pour 2 personnes'),
('3', 'Menu Thali Végétarien', '5', '0', 'Accompagné de Cheese Nan ou Nan Nature'),
('4', 'Menu spécial', '6', '0', 'avec 1 soupe, 1 salade, 1 riz');

-- ------------------------------------------------------------

--
-- Table structure for table `modifications`
--

CREATE TABLE `modifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tables` varchar(50) NOT NULL,
  `id_column` int(11) NOT NULL,
  `field` varchar(50) NOT NULL,
  `old_value` varchar(50) NOT NULL,
  `new_value` varchar(50) NOT NULL,
  `types` enum('CREATE','UPDATE','DELETE') NOT NULL,
  `id_user` int(11) NOT NULL,
  `date_modification` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `modifications`
--

;

-- ------------------------------------------------------------

--
-- Table structure for table `modifications_history`
--

CREATE TABLE `modifications_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tables` varchar(50) NOT NULL,
  `id_column` int(11) NOT NULL,
  `field` varchar(50) NOT NULL,
  `old_value` varchar(50) NOT NULL,
  `new_value` varchar(50) NOT NULL,
  `types` enum('CREATE','UPDATE','DELETE') NOT NULL,
  `id_user` int(11) NOT NULL,
  `date_modification` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_execution` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `modifications_history`
--

;

-- ------------------------------------------------------------

--
-- Table structure for table `panier`
--

CREATE TABLE `panier` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `id_restaurant` int(11) DEFAULT NULL,
  `rue` varchar(50) DEFAULT NULL,
  `ville` varchar(50) DEFAULT NULL,
  `code_postal` char(5) DEFAULT NULL,
  `complement` varchar(50) DEFAULT NULL,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `telephone` varchar(32) DEFAULT NULL,
  `distance` double DEFAULT NULL,
  `heure_souhaite` int(11) NOT NULL DEFAULT '-1',
  `minute_souhaite` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`),
  KEY `fk_panier_restaurant` (`id_restaurant`),
  CONSTRAINT `fk_panier_restaurant` FOREIGN KEY (`id_restaurant`) REFERENCES `restaurants` (`id`),
  CONSTRAINT `fk_panier_users` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `panier`
--

;

-- ------------------------------------------------------------

--
-- Table structure for table `panier_carte`
--

CREATE TABLE `panier_carte` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_panier` int(11) NOT NULL,
  `id_carte` int(11) NOT NULL,
  `id_format` int(11) NOT NULL,
  `quantite` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_panier_carte_panier` (`id_panier`),
  KEY `fk_panier_carte_carte` (`id_carte`),
  CONSTRAINT `fk_panier_carte_carte` FOREIGN KEY (`id_carte`) REFERENCES `carte` (`id`),
  CONSTRAINT `fk_panier_carte_panier` FOREIGN KEY (`id_panier`) REFERENCES `panier` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `panier_carte`
--

;

-- ------------------------------------------------------------

--
-- Table structure for table `panier_carte_accompagnement`
--

CREATE TABLE `panier_carte_accompagnement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_panier_carte` int(11) NOT NULL,
  `id_accompagnement` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_panier_carte_accompagnement_panier_carte` (`id_panier_carte`),
  KEY `fk_panier_carte_accompagnement_accompagnement` (`id_accompagnement`),
  CONSTRAINT `fk_panier_carte_accompagnement_accompagnement` FOREIGN KEY (`id_accompagnement`) REFERENCES `carte` (`id`),
  CONSTRAINT `fk_panier_carte_accompagnement_panier_carte` FOREIGN KEY (`id_panier_carte`) REFERENCES `panier_carte` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `panier_carte_accompagnement`
--

;

-- ------------------------------------------------------------

--
-- Table structure for table `panier_carte_option`
--

CREATE TABLE `panier_carte_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_panier_carte` int(11) NOT NULL,
  `id_option` int(11) NOT NULL,
  `id_value` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_panier_carte_option_panier_carte` (`id_panier_carte`),
  KEY `fk_panier_carte_option_option` (`id_option`),
  KEY `fk_panier_carte_option_value` (`id_value`),
  CONSTRAINT `fk_panier_carte_option_option` FOREIGN KEY (`id_option`) REFERENCES `restaurant_option` (`id`),
  CONSTRAINT `fk_panier_carte_option_panier_carte` FOREIGN KEY (`id_panier_carte`) REFERENCES `panier_carte` (`id`),
  CONSTRAINT `fk_panier_carte_option_value` FOREIGN KEY (`id_value`) REFERENCES `restaurant_option_value` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `panier_carte_option`
--

;

-- ------------------------------------------------------------

--
-- Table structure for table `panier_carte_supplement`
--

CREATE TABLE `panier_carte_supplement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_panier_carte` int(11) NOT NULL,
  `id_supplement` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_panier_carte_supplement_panier_carte` (`id_panier_carte`),
  KEY `fk_panier_carte_supplement_supplement` (`id_supplement`),
  CONSTRAINT `fk_panier_carte_supplement_panier_carte` FOREIGN KEY (`id_panier_carte`) REFERENCES `panier_carte` (`id`),
  CONSTRAINT `fk_panier_carte_supplement_supplement` FOREIGN KEY (`id_supplement`) REFERENCES `supplements` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `panier_carte_supplement`
--

;

-- ------------------------------------------------------------

--
-- Table structure for table `panier_menu`
--

CREATE TABLE `panier_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_panier` int(11) NOT NULL,
  `id_menu` int(11) NOT NULL,
  `id_format` int(11) NOT NULL,
  `id_formule` int(11) NOT NULL,
  `quantite` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_panier_menu_panier` (`id_panier`),
  KEY `fk_panier_menu_menus` (`id_menu`),
  KEY `fk_panier_menu_format` (`id_format`),
  KEY `fk_panier_menu_formule` (`id_formule`),
  CONSTRAINT `fk_panier_menu_format` FOREIGN KEY (`id_format`) REFERENCES `restaurant_format` (`id`),
  CONSTRAINT `fk_panier_menu_formule` FOREIGN KEY (`id_formule`) REFERENCES `restaurant_formule` (`id`),
  CONSTRAINT `fk_panier_menu_menus` FOREIGN KEY (`id_menu`) REFERENCES `menus` (`id`),
  CONSTRAINT `fk_panier_menu_panier` FOREIGN KEY (`id_panier`) REFERENCES `panier` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `panier_menu`
--

;

-- ------------------------------------------------------------

--
-- Table structure for table `panier_menu_accompagnement`
--

CREATE TABLE `panier_menu_accompagnement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_panier_menu_contenu` int(11) NOT NULL,
  `id_accompagnement` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_panier_menu_accompagnement_panier_carte` (`id_panier_menu_contenu`),
  KEY `fk_panier_menu_accompagnement_accompagnement` (`id_accompagnement`),
  CONSTRAINT `fk_panier_menu_accompagnement_accompagnement` FOREIGN KEY (`id_accompagnement`) REFERENCES `carte` (`id`),
  CONSTRAINT `fk_panier_menu_accompagnement_panier_carte` FOREIGN KEY (`id_panier_menu_contenu`) REFERENCES `panier_menu_contenu` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `panier_menu_accompagnement`
--

;

-- ------------------------------------------------------------

--
-- Table structure for table `panier_menu_contenu`
--

CREATE TABLE `panier_menu_contenu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_panier_menu` int(11) NOT NULL,
  `id_contenu` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_panier_contenu_panier_menu` (`id_panier_menu`),
  KEY `fk_panier_contenu_menu_contenu` (`id_contenu`),
  CONSTRAINT `fk_panier_contenu_menu_contenu` FOREIGN KEY (`id_contenu`) REFERENCES `menu_contenu` (`id`),
  CONSTRAINT `fk_panier_contenu_panier_menu` FOREIGN KEY (`id_panier_menu`) REFERENCES `panier_menu` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `panier_menu_contenu`
--

;

-- ------------------------------------------------------------

--
-- Table structure for table `panier_menu_supplement`
--

CREATE TABLE `panier_menu_supplement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_panier_menu_contenu` int(11) NOT NULL,
  `id_supplement` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_panier_menu_supplement_panier_carte` (`id_panier_menu_contenu`),
  KEY `fk_panier_menu_supplement_supplement` (`id_supplement`),
  CONSTRAINT `fk_panier_menu_supplement_panier_carte` FOREIGN KEY (`id_panier_menu_contenu`) REFERENCES `panier_menu_contenu` (`id`),
  CONSTRAINT `fk_panier_menu_supplement_supplement` FOREIGN KEY (`id_supplement`) REFERENCES `supplements` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `panier_menu_supplement`
--

;

-- ------------------------------------------------------------

--
-- Table structure for table `pre_commande`
--

CREATE TABLE `pre_commande` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `rue` varchar(50) DEFAULT NULL,
  `ville` varchar(50) DEFAULT NULL,
  `code_postal` char(5) DEFAULT NULL,
  `complement` varchar(50) DEFAULT NULL,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `telephone` varchar(32) DEFAULT NULL,
  `id_restaurant` int(11) DEFAULT NULL,
  `date_commande` date NOT NULL DEFAULT '0000-00-00',
  `heure_souhaite` int(11) NOT NULL DEFAULT '-1',
  `minute_souhaite` int(11) NOT NULL DEFAULT '0',
  `prix` double NOT NULL,
  `prix_livraison` double NOT NULL,
  `distance` double DEFAULT NULL,
  `validation` tinyint(1) NOT NULL DEFAULT '0',
  `date_validation` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `payment` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_pre_commande_users` (`uid`),
  KEY `fk_pre_commande_restaurant` (`id_restaurant`),
  CONSTRAINT `fk_pre_commande_restaurant` FOREIGN KEY (`id_restaurant`) REFERENCES `restaurants` (`id`),
  CONSTRAINT `fk_pre_commande_users` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pre_commande`
--

;

-- ------------------------------------------------------------

--
-- Table structure for table `pre_commande_carte`
--

CREATE TABLE `pre_commande_carte` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_commande` int(11) NOT NULL,
  `id_carte` int(11) NOT NULL,
  `id_format` int(11) NOT NULL,
  `quantite` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_pre_commande_carte_pre_commande` (`id_commande`),
  KEY `fk_pre_commande_carte_carte` (`id_carte`),
  CONSTRAINT `fk_pre_commande_carte_carte` FOREIGN KEY (`id_carte`) REFERENCES `carte` (`id`),
  CONSTRAINT `fk_pre_commande_carte_pre_commande` FOREIGN KEY (`id_commande`) REFERENCES `pre_commande` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pre_commande_carte`
--

;

-- ------------------------------------------------------------

--
-- Table structure for table `pre_commande_carte_accompagnement`
--

CREATE TABLE `pre_commande_carte_accompagnement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_commande_carte` int(11) NOT NULL,
  `id_accompagnement` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_pcca_pcc` (`id_commande_carte`),
  KEY `fk_pcca_carte` (`id_accompagnement`),
  CONSTRAINT `fk_pcca_carte` FOREIGN KEY (`id_accompagnement`) REFERENCES `carte` (`id`),
  CONSTRAINT `fk_pcca_pcc` FOREIGN KEY (`id_commande_carte`) REFERENCES `pre_commande_carte` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pre_commande_carte_accompagnement`
--

;

-- ------------------------------------------------------------

--
-- Table structure for table `pre_commande_carte_option`
--

CREATE TABLE `pre_commande_carte_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_commande_carte` int(11) NOT NULL,
  `id_option` int(11) NOT NULL,
  `id_value` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_pcco_pcc` (`id_commande_carte`),
  KEY `fk_pcco_option` (`id_option`),
  KEY `fk_pcco_option_value` (`id_value`),
  CONSTRAINT `fk_pcco_option` FOREIGN KEY (`id_option`) REFERENCES `restaurant_option` (`id`),
  CONSTRAINT `fk_pcco_option_value` FOREIGN KEY (`id_value`) REFERENCES `restaurant_option_value` (`id`),
  CONSTRAINT `fk_pcco_pcc` FOREIGN KEY (`id_commande_carte`) REFERENCES `pre_commande_carte` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pre_commande_carte_option`
--

;

-- ------------------------------------------------------------

--
-- Table structure for table `pre_commande_carte_supplement`
--

CREATE TABLE `pre_commande_carte_supplement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_commande_carte` int(11) NOT NULL,
  `id_supplement` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_pccs_pcc` (`id_commande_carte`),
  KEY `fk_pccs_supplement` (`id_supplement`),
  CONSTRAINT `fk_pccs_pcc` FOREIGN KEY (`id_commande_carte`) REFERENCES `pre_commande_carte` (`id`),
  CONSTRAINT `fk_pccs_supplement` FOREIGN KEY (`id_supplement`) REFERENCES `supplements` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pre_commande_carte_supplement`
--

;

-- ------------------------------------------------------------

--
-- Table structure for table `pre_commande_menu`
--

CREATE TABLE `pre_commande_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_commande` int(11) NOT NULL,
  `id_menu` int(11) NOT NULL,
  `id_format` int(11) NOT NULL,
  `id_formule` int(11) NOT NULL,
  `quantite` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_pre_commande_menu_commande` (`id_commande`),
  KEY `fk_pre_commande_menu_menus` (`id_menu`),
  KEY `fk_pre_commande_menu_format` (`id_format`),
  KEY `fk_pre_commande_menu_formule` (`id_formule`),
  CONSTRAINT `fk_pre_commande_menu_commande` FOREIGN KEY (`id_commande`) REFERENCES `pre_commande` (`id`),
  CONSTRAINT `fk_pre_commande_menu_format` FOREIGN KEY (`id_format`) REFERENCES `restaurant_format` (`id`),
  CONSTRAINT `fk_pre_commande_menu_formule` FOREIGN KEY (`id_formule`) REFERENCES `restaurant_formule` (`id`),
  CONSTRAINT `fk_pre_commande_menu_menus` FOREIGN KEY (`id_menu`) REFERENCES `menus` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pre_commande_menu`
--

;

-- ------------------------------------------------------------

--
-- Table structure for table `pre_commande_menu_accompagnement`
--

CREATE TABLE `pre_commande_menu_accompagnement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_commande_menu_contenu` int(11) NOT NULL,
  `id_accompagnement` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_pcma_pcmc` (`id_commande_menu_contenu`),
  KEY `fk_pcma_accompagnement` (`id_accompagnement`),
  CONSTRAINT `fk_pcma_accompagnement` FOREIGN KEY (`id_accompagnement`) REFERENCES `carte` (`id`),
  CONSTRAINT `fk_pcma_pcmc` FOREIGN KEY (`id_commande_menu_contenu`) REFERENCES `pre_commande_menu_contenu` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pre_commande_menu_accompagnement`
--

;

-- ------------------------------------------------------------

--
-- Table structure for table `pre_commande_menu_contenu`
--

CREATE TABLE `pre_commande_menu_contenu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_commande_menu` int(11) NOT NULL,
  `id_contenu` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_pcmc_pcm` (`id_commande_menu`),
  KEY `fk_pcmc_menu_contenu` (`id_contenu`),
  CONSTRAINT `fk_pcmc_menu_contenu` FOREIGN KEY (`id_contenu`) REFERENCES `menu_contenu` (`id`),
  CONSTRAINT `fk_pcmc_pcm` FOREIGN KEY (`id_commande_menu`) REFERENCES `pre_commande_menu` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pre_commande_menu_contenu`
--

;

-- ------------------------------------------------------------

--
-- Table structure for table `pre_commande_menu_supplement`
--

CREATE TABLE `pre_commande_menu_supplement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_commande_menu_contenu` int(11) NOT NULL,
  `id_supplement` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_pcms_pcmc` (`id_commande_menu_contenu`),
  KEY `fk_pcms_supplement` (`id_supplement`),
  CONSTRAINT `fk_pcms_pcmc` FOREIGN KEY (`id_commande_menu_contenu`) REFERENCES `pre_commande_menu_contenu` (`id`),
  CONSTRAINT `fk_pcms_supplement` FOREIGN KEY (`id_supplement`) REFERENCES `supplements` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pre_commande_menu_supplement`
--

;

-- ------------------------------------------------------------

--
-- Table structure for table `prix_livraison`
--

CREATE TABLE `prix_livraison` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_restaurant` int(11) NOT NULL,
  `distance_min` int(11) NOT NULL,
  `distance_max` int(11) NOT NULL,
  `prix` double NOT NULL,
  `montant_min` int(11) NOT NULL,
  `reduction_premium` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `prix_livraison`
--

INSERT INTO `prix_livraison` (`id`, `id_restaurant`, `distance_min`, `distance_max`, `prix`, `montant_min`, `reduction_premium`) VALUES
('1', '0', '0', '5', '2.5', '10', '1'),
('2', '0', '5', '10', '4', '15', '1.5'),
('3', '0', '10', '15', '7', '20', '2'),
('4', '0', '15', '20', '10', '30', '2.5'),
('5', '0', '20', '-1', '15', '50', '3');

-- ------------------------------------------------------------

--
-- Table structure for table `restaurant_categorie`
--

CREATE TABLE `restaurant_categorie` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_categorie` int(11) NOT NULL DEFAULT '0',
  `id_restaurant` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `ordre` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_restaurant_categorie_restaurant` (`id_restaurant`),
  CONSTRAINT `fk_restaurant_categorie_restaurant` FOREIGN KEY (`id_restaurant`) REFERENCES `restaurants` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `restaurant_categorie`
--

INSERT INTO `restaurant_categorie` (`id`, `parent_categorie`, `id_restaurant`, `nom`, `ordre`) VALUES
('1', '0', '1', 'pizza', '0'),
('2', '1', '1', 'normal', '0'),
('3', '0', '4', 'Steakhouse', '0'),
('4', '0', '4', 'Autres plats', '0'),
('5', '0', '1', 'test', '0'),
('6', '0', '5', 'Entrées', '0'),
('7', '0', '5', 'Entrées « Spécial Mabaraja »', '0'),
('8', '0', '5', 'Pains maison', '0'),
('9', '0', '5', 'Poulet', '0'),
('10', '0', '5', 'Boeuf', '0'),
('11', '0', '5', 'Agneau', '0'),
('12', '0', '5', 'Biryani', '0'),
('13', '0', '5', 'Poissons et Crevettes', '0'),
('14', '0', '5', 'Plats végétariens', '0'),
('15', '0', '5', 'Riz Basmati', '0'),
('16', '0', '5', 'Nos desserts et boissons', '0'),
('17', '0', '5', 'Lassi (boisson au yaourt)', '0'),
('18', '0', '5', 'Eaux Minérales', '0'),
('19', '0', '5', 'Boissons Fraîches', '0'),
('20', '0', '5', 'Carte des vins', '0'),
('21', '20', '5', 'Rouges', '0'),
('22', '20', '5', 'Rosés', '0'),
('23', '20', '5', 'Blanc', '0'),
('24', '20', '5', 'Champagne aoc', '0'),
('25', '0', '6', 'Menu midi & soir', '0'),
('26', '0', '6', 'Autre', '0'),
('27', '0', '6', 'Menu chirashi', '0'),
('28', '0', '6', 'Hors d''oeuvre et accompagnement', '0'),
('29', '0', '6', 'Sushi (servis par paire)', '0'),
('30', '0', '7', 'Les pizzas ou bruschettas', '0'),
('31', '30', '7', 'Les classqiues', '0'),
('32', '30', '7', 'Les originales', '0'),
('33', '0', '7', 'autres', '0'),
('34', '0', '7', 'Plateau Apéro', '0'),
('35', '0', '7', 'Plats', '0'),
('36', '0', '7', 'Boissons', '0'),
('37', '0', '7', 'Vins', '0'),
('38', '0', '8', 'Boissons diverses', '0'),
('39', '0', '8', 'Vins rouges', '0'),
('40', '0', '8', 'Vins rosés', '0'),
('41', '40', '8', 'Vins de France', '0'),
('42', '40', '8', 'Vins d''Italie', '0'),
('43', '0', '8', 'Vins blancs', '0'),
('44', '0', '8', 'Hors d''oeuvre', '0'),
('45', '0', '8', 'Nos grandes assiettes', '0'),
('46', '0', '8', 'Pasta', '0'),
('47', '0', '8', 'Spécialités', '0'),
('48', '0', '8', 'Pizza', '0'),
('49', '0', '8', 'Desserts', '0'),
('50', '49', '8', 'Gourmandises', '0'),
('51', '0', '6', 'Sashimi (servis par 10 pcs)', '0'),
('52', '0', '6', 'Maki (servis par 6 pcs)', '0'),
('53', '0', '6', 'Maki printemps servis par 6 pcs', '0'),
('54', '0', '6', 'Soja rolls', '0'),
('55', '0', '6', 'California rolls (servis par 6 p', '0'),
('56', '0', '6', 'Brochette (servis par paire)', '0'),
('57', '0', '6', 'Dessert', '0'),
('58', '0', '6', 'Boissons', '0'),
('59', '0', '7', 'Dessert', '0');

-- ------------------------------------------------------------

--
-- Table structure for table `restaurant_format`
--

CREATE TABLE `restaurant_format` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_restaurant` int(11) NOT NULL,
  `nom` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_restaurant_format_restaurant` (`id_restaurant`),
  CONSTRAINT `fk_restaurant_format_restaurant` FOREIGN KEY (`id_restaurant`) REFERENCES `restaurants` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `restaurant_format`
--

INSERT INTO `restaurant_format` (`id`, `id_restaurant`, `nom`) VALUES
('1', '1', ''),
('2', '2', ''),
('3', '3', ''),
('4', '4', ''),
('5', '5', ''),
('6', '5', 'Sucré'),
('7', '5', 'Nature'),
('8', '5', 'Salé'),
('9', '5', 'Mangue'),
('10', '5', 'Banane'),
('11', '5', '37.5 cl'),
('12', '5', '75 cl'),
('13', '5', '50 cl'),
('14', '5', '100 cl'),
('15', '6', ''),
('16', '7', ''),
('17', '7', 'Petit modèle'),
('18', '7', 'Grand modèle'),
('19', '8', ''),
('20', '6', '37,5cl'),
('21', '6', '75cl'),
('22', '7', 'pizza'),
('23', '7', 'Bruschettas');

-- ------------------------------------------------------------

--
-- Table structure for table `restaurant_formule`
--

CREATE TABLE `restaurant_formule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_restaurant` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_restaurant_formule_restaurants` (`id_restaurant`),
  CONSTRAINT `fk_restaurant_formule_restaurants` FOREIGN KEY (`id_restaurant`) REFERENCES `restaurants` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `restaurant_formule`
--

INSERT INTO `restaurant_formule` (`id`, `id_restaurant`, `nom`) VALUES
('1', '5', 'Entrée - plat - dessert'),
('2', '5', 'Entrée - plat - dessert - vin - café'),
('3', '5', 'Plat - dessert'),
('4', '6', '2 plats au choix');

-- ------------------------------------------------------------

--
-- Table structure for table `restaurant_horaires`
--

CREATE TABLE `restaurant_horaires` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_restaurant` int(11) NOT NULL,
  `id_jour` int(11) NOT NULL,
  `heure_debut` int(11) NOT NULL,
  `minute_debut` int(11) NOT NULL DEFAULT '0',
  `heure_fin` int(11) NOT NULL,
  `minute_fin` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_restaurant_horaires_restaurant` (`id_restaurant`),
  KEY `fk_restaurant_horaires_day` (`id_jour`),
  CONSTRAINT `fk_restaurant_horaires_day` FOREIGN KEY (`id_jour`) REFERENCES `days` (`id`),
  CONSTRAINT `fk_restaurant_horaires_restaurant` FOREIGN KEY (`id_restaurant`) REFERENCES `restaurants` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `restaurant_horaires`
--

INSERT INTO `restaurant_horaires` (`id`, `id_restaurant`, `id_jour`, `heure_debut`, `minute_debut`, `heure_fin`, `minute_fin`) VALUES
('1', '1', '2', '18', '30', '22', '30'),
('2', '1', '3', '18', '30', '22', '30'),
('3', '1', '4', '18', '30', '22', '30'),
('4', '1', '5', '18', '30', '22', '30'),
('5', '1', '6', '18', '30', '22', '30'),
('6', '1', '7', '18', '30', '22', '30'),
('7', '2', '1', '18', '0', '23', '0'),
('8', '2', '2', '18', '0', '23', '0'),
('9', '2', '3', '18', '0', '23', '0'),
('10', '2', '4', '18', '0', '23', '0'),
('11', '2', '5', '18', '0', '23', '0'),
('12', '2', '6', '18', '0', '23', '0'),
('13', '2', '7', '18', '0', '23', '0'),
('14', '3', '1', '18', '0', '23', '0'),
('15', '3', '2', '18', '0', '23', '0'),
('16', '3', '3', '18', '0', '23', '0'),
('17', '3', '4', '18', '0', '23', '0'),
('18', '3', '5', '18', '0', '23', '0'),
('19', '3', '6', '18', '0', '23', '0'),
('20', '3', '7', '18', '0', '23', '0'),
('21', '4', '1', '18', '0', '23', '0'),
('22', '4', '2', '18', '0', '23', '0'),
('23', '4', '3', '18', '0', '23', '0'),
('24', '4', '4', '18', '0', '23', '0'),
('25', '4', '5', '18', '0', '23', '0'),
('26', '4', '6', '18', '0', '23', '0'),
('27', '4', '7', '18', '0', '23', '0'),
('28', '5', '1', '19', '0', '23', '0'),
('29', '5', '2', '19', '0', '23', '0'),
('30', '5', '3', '19', '0', '23', '0'),
('31', '5', '4', '19', '0', '23', '0'),
('32', '5', '5', '19', '0', '23', '0'),
('33', '5', '6', '19', '0', '23', '0'),
('34', '5', '7', '19', '0', '23', '0'),
('35', '6', '1', '19', '0', '22', '30'),
('36', '6', '2', '19', '0', '22', '30'),
('37', '6', '3', '19', '0', '22', '30'),
('38', '6', '4', '19', '0', '22', '30'),
('39', '6', '5', '19', '0', '22', '30'),
('40', '6', '6', '19', '0', '22', '30'),
('41', '6', '7', '19', '0', '22', '30'),
('42', '7', '2', '19', '0', '21', '45'),
('43', '7', '3', '19', '0', '21', '45'),
('44', '7', '4', '19', '0', '21', '45'),
('45', '7', '5', '19', '0', '21', '45'),
('46', '7', '6', '19', '0', '21', '45'),
('47', '8', '1', '19', '0', '23', '0'),
('48', '8', '2', '19', '0', '23', '0'),
('49', '8', '3', '19', '0', '23', '0'),
('50', '8', '4', '19', '0', '23', '0'),
('51', '8', '5', '19', '0', '23', '0'),
('52', '8', '6', '19', '0', '23', '0'),
('53', '8', '7', '19', '0', '23', '0');

-- ------------------------------------------------------------

--
-- Table structure for table `restaurant_option`
--

CREATE TABLE `restaurant_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_restaurant` int(11) NOT NULL,
  `nom` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_restaurant_option_restaurant` (`id_restaurant`),
  CONSTRAINT `fk_restaurant_option_restaurant` FOREIGN KEY (`id_restaurant`) REFERENCES `restaurants` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `restaurant_option`
--

INSERT INTO `restaurant_option` (`id`, `id_restaurant`, `nom`) VALUES
('1', '4', 'cuisson'),
('2', '1', 'cuisson'),
('3', '5', 'Type de plat'),
('4', '5', 'Parfum N°1'),
('5', '5', 'Parfum N°2'),
('6', '6', 'Makis'),
('7', '6', 'Makis & Sushis'),
('8', '6', 'Brochettes'),
('9', '6', 'Brochettes'),
('10', '6', 'Dessert'),
('11', '6', 'second dessert'),
('12', '6', 'Nêms'),
('13', '8', 'jus'),
('14', '6', 'maki'),
('15', '6', 'parfum');

-- ------------------------------------------------------------

--
-- Table structure for table `restaurant_option_value`
--

CREATE TABLE `restaurant_option_value` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_option` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `ordre` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_restaurant_option_value_restaurant_option` (`id_option`),
  CONSTRAINT `fk_restaurant_option_value_restaurant_option` FOREIGN KEY (`id_option`) REFERENCES `restaurant_option` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `restaurant_option_value`
--

INSERT INTO `restaurant_option_value` (`id`, `id_option`, `nom`, `ordre`) VALUES
('1', '1', 'bien cuit', '0'),
('2', '1', 'a point', '0'),
('3', '1', 'saignant', '0'),
('4', '2', 'a point', '0'),
('5', '2', 'bien cuit', '0'),
('8', '3', 'Relevé', '0'),
('9', '3', 'Doux', '0'),
('10', '4', 'Vanille', '0'),
('11', '4', 'Mangue', '0'),
('12', '4', 'Passion', '0'),
('13', '4', 'Fraise', '0'),
('14', '5', 'Mangue', '0'),
('15', '5', 'Passion', '0'),
('16', '5', 'Vanille', '0'),
('17', '5', 'Fraise', '0'),
('18', '6', 'Futomakis', '0'),
('19', '6', 'Uramaki', '0'),
('20', '7', '8 Makis royale, 8 Makis printemps, 3 Sushis saumon', '0'),
('21', '7', '8 Makis royal, 6 Sushis (saumon et thon)', '0'),
('22', '8', '5 Brochettes : 1 poulet, 1 boulette de poulet, 1 boeuf, 1 boeuf au fromage, 1 aile de poulet', '0'),
('23', '8', '4 Brochettes de boeuf au fromage', '0'),
('24', '9', '4 brochettes : 2 saumon, 2 thon', '0'),
('25', '9', '2 gambas', '0'),
('26', '10', 'Lychee', '0'),
('27', '10', 'Nougat', '0'),
('28', '10', 'Perle de coco', '0'),
('29', '10', '2 boules de glaces', '0'),
('30', '11', 'Lychee', '0'),
('31', '11', 'Nougat', '0'),
('32', '11', 'Perle de coco', '0'),
('33', '11', '2 boules de glaces', '0'),
('34', '12', 'poulet', '0'),
('35', '12', 'crevettes', '0'),
('36', '13', 'Orange', '0'),
('37', '13', 'Carotte', '0'),
('38', '13', 'Ananas', '0'),
('39', '13', 'Tomate', '0'),
('40', '14', 'Futomaki', '0'),
('41', '14', 'California futomaki', '0'),
('42', '15', 'orange', '0'),
('43', '15', 'ananas', '0'),
('44', '15', 'abricot', '0'),
('45', '15', 'litchi', '0');

-- ------------------------------------------------------------

--
-- Table structure for table `restaurant_tag`
--

CREATE TABLE `restaurant_tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_restaurant` int(11) NOT NULL,
  `id_tag` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_restaurant_tag_restaurant` (`id_restaurant`),
  KEY `fk_restaurant_tag_tag` (`id_tag`),
  CONSTRAINT `fk_restaurant_tag_restaurant` FOREIGN KEY (`id_restaurant`) REFERENCES `restaurants` (`id`),
  CONSTRAINT `fk_restaurant_tag_tag` FOREIGN KEY (`id_tag`) REFERENCES `tags` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `restaurant_tag`
--

;

-- ------------------------------------------------------------

--
-- Table structure for table `restaurant_virement`
--

CREATE TABLE `restaurant_virement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_restaurant` int(11) NOT NULL,
  `montant` double NOT NULL,
  `date_virement` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `validation` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `commentaire` text,
  PRIMARY KEY (`id`),
  KEY `fk_restaurant_virement_restaurant` (`id_restaurant`),
  CONSTRAINT `fk_restaurant_virement_restaurant` FOREIGN KEY (`id_restaurant`) REFERENCES `restaurants` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `restaurant_virement`
--

;

-- ------------------------------------------------------------

--
-- Table structure for table `restaurants`
--

CREATE TABLE `restaurants` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(32) NOT NULL,
  `rue` varchar(32) NOT NULL,
  `ville` varchar(32) NOT NULL,
  `code_postal` varchar(32) NOT NULL,
  `telephone` varchar(32) DEFAULT NULL,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `short_desc` varchar(200) NOT NULL,
  `long_desc` text,
  `score` double NOT NULL DEFAULT '0',
  `is_top` tinyint(1) NOT NULL DEFAULT '0',
  `enabled` tinyint(1) NOT NULL DEFAULT '0',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `pourcentage` int(11) NOT NULL DEFAULT '30',
  `virement` enum('JOURNALIER','HEBDOMADAIRE','MENSUEL') NOT NULL DEFAULT 'HEBDOMADAIRE',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `restaurants`
--

INSERT INTO `restaurants` (`id`, `nom`, `rue`, `ville`, `code_postal`, `telephone`, `latitude`, `longitude`, `short_desc`, `long_desc`, `score`, `is_top`, `enabled`, `deleted`, `pourcentage`, `virement`) VALUES
('1', 'Mac pizza', '78 avenue de Paris', 'Juziers', '78820', '0134929243', '48.9904277', '1.8480862', 'Pizzeria', '', '0', '0', '0', '0', '30', 'HEBDOMADAIRE'),
('2', 'La Crêp''itante', '92 Avenue de Paris', 'Juziers', '78820', '0130901526', '48.9902308', '1.8488881000000674', 'Crêperie', '', '0', '0', '0', '0', '30', 'HEBDOMADAIRE'),
('3', 'Jardin du Kashmir', '36 Boulevard Carnot', 'Hardricourt', '78250', '0130220170', '49.0051269', '1.9016239000000041', 'restaurant indien', '', '0', '0', '0', '0', '30', 'HEBDOMADAIRE'),
('4', 'Buffalo grill', 'undefined Rue du 8 Mai 1945', 'Mantes-la-Ville', '78711', '0130924242', '48.9760062', '1.7187407999999778', 'Steakhouse', '', '0', '0', '0', '0', '30', 'HEBDOMADAIRE'),
('5', 'Taj Mahal', '14 Rue Henri Rivière', 'Mantes-la-Jolie', '78200', '0130940134', '48.9895562', '1.7158738', 'Restaurant Indien', '', '0', '0', '1', '0', '30', 'HEBDOMADAIRE'),
('6', 'Sushi Les Mureaux', '7 Rue de Seine', 'Les Mureaux', '78130', '0130919768', '48.9976189', '1.9103696', 'Restaurant Japonais', '', '0', '0', '1', '0', '15', 'HEBDOMADAIRE'),
('7', 'Chez Antoine', '16 Rue Gambetta', 'Mantes-la-Jolie', '78200', '0130927777', '48.9894477', '1.7165662', 'Cuisine Française', 'Chef de métier, Antoine vous propose une cuisine qui sait mêler avec simplicité et subtilité le meilleur des saveurs provençales et italiennes. La carte généreuse et gourmande vous propose tout une variété de plats tous élaborés avec soins dans le respect des produits.', '0', '0', '1', '0', '30', 'HEBDOMADAIRE'),
('8', 'Dalla Famiglia', '23 Rue de Lorraine', 'Mantes-la-Jolie', '78200', '0134784638', '48.9877018', '1.7129632', 'Restaurant Italien', '', '0', '0', '1', '0', '30', 'HEBDOMADAIRE');

-- ------------------------------------------------------------

--
-- Table structure for table `supplements`
--

CREATE TABLE `supplements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(32) NOT NULL,
  `id_restaurant` int(11) NOT NULL,
  `prix` double NOT NULL,
  `commentaire` text,
  PRIMARY KEY (`id`),
  KEY `fk_supplement_restaurant` (`id_restaurant`),
  CONSTRAINT `fk_supplement_restaurant` FOREIGN KEY (`id_restaurant`) REFERENCES `restaurants` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `supplements`
--

INSERT INTO `supplements` (`id`, `nom`, `id_restaurant`, `prix`, `commentaire`) VALUES
('1', 'thon', '1', '1', ''),
('2', 'tomate', '1', '0.5', ''),
('3', 'Salade', '7', '1.5', '');

-- ------------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tags`
--

;

-- ------------------------------------------------------------

--
-- Table structure for table `update_distance_dispo`
--

CREATE TABLE `update_distance_dispo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_dispo` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_update_distance_dispo_dispo` (`id_dispo`),
  CONSTRAINT `fk_update_distance_dispo_dispo` FOREIGN KEY (`id_dispo`) REFERENCES `user_livreur_dispo` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `update_distance_dispo`
--

;

-- ------------------------------------------------------------

--
-- Table structure for table `update_distance_restaurant`
--

CREATE TABLE `update_distance_restaurant` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_restaurant` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_update_distance_restaurant_restaurant` (`id_restaurant`),
  CONSTRAINT `fk_update_distance_restaurant_restaurant` FOREIGN KEY (`id_restaurant`) REFERENCES `restaurants` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `update_distance_restaurant`
--

INSERT INTO `update_distance_restaurant` (`id`, `id_restaurant`) VALUES
('1', '5'),
('2', '6'),
('3', '7'),
('4', '8');

-- ------------------------------------------------------------

--
-- Table structure for table `user_client`
--

CREATE TABLE `user_client` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `rue` varchar(50) DEFAULT NULL,
  `ville` varchar(50) DEFAULT NULL,
  `code_postal` char(5) DEFAULT NULL,
  `complement` varchar(50) DEFAULT NULL,
  `telephone` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`),
  CONSTRAINT `fk_user_client_users` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_client`
--

INSERT INTO `user_client` (`id`, `uid`, `rue`, `ville`, `code_postal`, `complement`, `telephone`) VALUES
('1', '4', '22 Rue du Commerce', 'Juziers', '78820', '', '0636601045'),
('2', '6', '', '', '', '', ''),
('3', '7', '', '', '', '', '');

-- ------------------------------------------------------------

--
-- Table structure for table `user_client_information`
--

CREATE TABLE `user_client_information` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `information` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`),
  CONSTRAINT `fk_uci_users` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_client_information`
--

;

-- ------------------------------------------------------------

--
-- Table structure for table `user_client_premium`
--

CREATE TABLE `user_client_premium` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `validation_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `subscribe_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`),
  CONSTRAINT `fk_ucp_users` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_client_premium`
--

;

-- ------------------------------------------------------------

--
-- Table structure for table `user_livreur`
--

CREATE TABLE `user_livreur` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `is_ready` tinyint(1) NOT NULL DEFAULT '0',
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `telephone` varchar(32) DEFAULT NULL,
  `rue` varchar(32) NOT NULL,
  `ville` varchar(32) NOT NULL,
  `code_postal` varchar(32) NOT NULL,
  `virement` enum('JOURNALIER','HEBDOMADAIRE','MENSUEL') NOT NULL DEFAULT 'HEBDOMADAIRE',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`),
  CONSTRAINT `fk_user_livreur_users` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_livreur`
--

INSERT INTO `user_livreur` (`id`, `uid`, `is_ready`, `latitude`, `longitude`, `telephone`, `rue`, `ville`, `code_postal`, `virement`) VALUES
('1', '3', '1', '0', '0', '0100000000', '', '', '', 'HEBDOMADAIRE');

-- ------------------------------------------------------------

--
-- Table structure for table `user_livreur_dispo`
--

CREATE TABLE `user_livreur_dispo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `rue` varchar(32) DEFAULT NULL,
  `ville` varchar(32) DEFAULT NULL,
  `code_postal` char(5) DEFAULT NULL,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `perimetre` int(11) NOT NULL,
  `vehicule` enum('VOITURE','SCOOTER','MOTO','VELO') NOT NULL DEFAULT 'VOITURE',
  `id_jour` int(11) NOT NULL,
  `heure_debut` int(11) NOT NULL,
  `minute_debut` int(11) NOT NULL DEFAULT '0',
  `heure_fin` int(11) NOT NULL,
  `minute_fin` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_user_livreur_dispo_users` (`uid`),
  KEY `fk_user_livreur_dispo_day` (`id_jour`),
  CONSTRAINT `fk_user_livreur_dispo_day` FOREIGN KEY (`id_jour`) REFERENCES `days` (`id`),
  CONSTRAINT `fk_user_livreur_dispo_users` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_livreur_dispo`
--

INSERT INTO `user_livreur_dispo` (`id`, `uid`, `rue`, `ville`, `code_postal`, `latitude`, `longitude`, `perimetre`, `vehicule`, `id_jour`, `heure_debut`, `minute_debut`, `heure_fin`, `minute_fin`) VALUES
('1', '3', '22 Rue du Commerce', 'Juziers', '78820', '48.9926352', '1.8485279999999875', '3', 'VOITURE', '1', '16', '15', '22', '45'),
('2', '3', '22 Rue du Commerce', 'Juziers', '78820', '48.9926352', '1.8485279999999875', '10', 'VOITURE', '3', '1', '0', '23', '0'),
('3', '3', '22 Rue du Commerce', 'Juziers', '78820', '48.9926352', '1.8485279999999875', '15', 'VOITURE', '4', '1', '0', '23', '0'),
('4', '3', '22 Rue du Commerce', 'Juziers', '78820', '48.9926352', '1.8485279999999875', '15', 'VOITURE', '4', '1', '0', '22', '0'),
('5', '3', '22 Rue du Commerce', 'Juziers', '78820', '48.9926352', '1.8485279999999875', '15', 'VOITURE', '5', '1', '0', '23', '0');

-- ------------------------------------------------------------

--
-- Table structure for table `user_livreur_position`
--

CREATE TABLE `user_livreur_position` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_livreur` int(11) NOT NULL,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_livreur_position`
--

;

-- ------------------------------------------------------------

--
-- Table structure for table `user_livreur_virement`
--

CREATE TABLE `user_livreur_virement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_restaurant` int(11) NOT NULL,
  `montant` double NOT NULL,
  `date_virement` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `validation` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `commentaire` text,
  PRIMARY KEY (`id`),
  KEY `fk_user_livreur_virement_restaurant` (`id_restaurant`),
  CONSTRAINT `fk_user_livreur_virement_restaurant` FOREIGN KEY (`id_restaurant`) REFERENCES `restaurants` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_livreur_virement`
--

;

-- ------------------------------------------------------------

--
-- Table structure for table `user_restaurant`
--

CREATE TABLE `user_restaurant` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `id_restaurant` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`),
  KEY `fk_user_restaurant_restaurant` (`id_restaurant`),
  CONSTRAINT `fk_user_restaurant_restaurant` FOREIGN KEY (`id_restaurant`) REFERENCES `restaurants` (`id`),
  CONSTRAINT `fk_user_restaurant_users` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_restaurant`
--

INSERT INTO `user_restaurant` (`id`, `uid`, `id_restaurant`) VALUES
('1', '2', '1'),
('2', '5', '1'),
('3', '8', '5'),
('4', '9', '5');

-- ------------------------------------------------------------

--
-- Table structure for table `user_session`
--

CREATE TABLE `user_session` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `session_key` varchar(32) NOT NULL,
  `date_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_logout` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `session_max_time` int(11) NOT NULL DEFAULT '-1',
  `gcm_token` text,
  PRIMARY KEY (`id`),
  KEY `fk_user_session_users` (`uid`),
  KEY `session_key` (`session_key`),
  CONSTRAINT `fk_user_session_users` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_session`
--

INSERT INTO `user_session` (`id`, `uid`, `session_key`, `date_login`, `date_logout`, `session_max_time`, `gcm_token`) VALUES
('1', '1', '0_O_uwiaBHaJ2PyJAeKgov_2hXGT2JKZ', '2016-05-16 07:07:46', '0000-00-00 00:00:00', '-1', ''),
('2', '1', 'IiNA1PfxDXi_IEhtF6OEYEvQ9C5nY64G', '2016-05-16 07:47:34', '0000-00-00 00:00:00', '-1', ''),
('3', '1', 'IiNA1PfxDXi_IEhtF6OEYEvQ9C5nY64G', '2016-05-16 07:47:34', '0000-00-00 00:00:00', '-1', ''),
('4', '1', 'fZa72ctC-werl-9y_62qVd_LTmUlxcw1', '2016-05-16 07:50:09', '0000-00-00 00:00:00', '-1', ''),
('5', '1', 'fZa72ctC-werl-9y_62qVd_LTmUlxcw1', '2016-05-16 07:50:09', '0000-00-00 00:00:00', '-1', ''),
('6', '1', 'lbkZMfCoUrWZbEs_lO3GPQERSv9RQ016', '2016-05-16 07:54:54', '0000-00-00 00:00:00', '-1', ''),
('7', '1', 'OzXNQZ_JjGab_x-uezvHBCeIsvG4x1dC', '2016-05-17 07:38:57', '0000-00-00 00:00:00', '-1', ''),
('8', '5', 'UNju2-rpb7Fc3yT2gbbHP0K3WbdapPDb', '2016-05-17 20:02:35', '0000-00-00 00:00:00', '-1', ''),
('9', '1', '74G4GOaLjvqTYLgn7gNCS__yy6-0hq1b', '2016-05-19 12:12:36', '0000-00-00 00:00:00', '-1', ''),
('10', '5', 'vBjcnTe7g4qUSzsSQgqFR8N_pnXgVdsI', '2016-05-19 12:15:00', '0000-00-00 00:00:00', '-1', ''),
('11', '3', 'wvJaO-KdW6VOdxvzd8DDr5RG8lCqmgaD', '2016-05-19 12:16:39', '0000-00-00 00:00:00', '-1', ''),
('12', '3', 'hU2zkzxzi4peZJNNinmvD9phUSgYVH6k', '2016-05-19 12:53:23', '0000-00-00 00:00:00', '-1', ''),
('13', '3', 'kq5M7urVF4eIyI3Re299AIXAoVHgTd0S', '2016-05-19 12:55:16', '0000-00-00 00:00:00', '-1', ''),
('14', '1', 'nZbby32BTP5xigwmjXM0xyWUOeAXSeXz', '2016-05-20 08:14:00', '0000-00-00 00:00:00', '-1', ''),
('15', '4', 'xChoul920BmAE0L-3K15Rk_4oNDMElcH', '2016-05-23 07:54:35', '2016-05-28 00:44:07', '-1', ''),
('16', '5', '633gPoEwb9Mrgc8o7Oncqy6eNXDjcarD', '2016-05-23 19:28:57', '0000-00-00 00:00:00', '-1', ''),
('17', '2', 'IFuVg6k0Tv8DoZ0hSKqaa5bSzBmNoTMx', '2016-05-23 19:47:41', '0000-00-00 00:00:00', '-1', ''),
('18', '4', '9mOcM1UuvbNYbvXbcmwigY5x12tvLbsh', '2016-05-25 00:20:53', '2016-05-28 00:44:07', '-1', ''),
('19', '1', '6JHvlFBE5Vmw1dnS3oTZ9_Ucl25WtmxX', '2016-05-25 23:57:08', '0000-00-00 00:00:00', '-1', ''),
('20', '1', 'pFjIEQwziHR7Zg9IR0a_-4jbgD1T0IRh', '2016-05-26 11:39:56', '0000-00-00 00:00:00', '-1', ''),
('21', '1', 'wj7izynbjMjECRfwmLo-WerUpU3VhQ7y', '2016-05-27 07:20:54', '0000-00-00 00:00:00', '-1', ''),
('22', '1', 'apnuPG9CPknUA36DpNtPxQXUePpRY4Ai', '2016-05-27 18:18:15', '0000-00-00 00:00:00', '-1', ''),
('23', '1', '2PN0wAbmr-UcOLJ0_gvs5KgiyUTVorZe', '2016-05-27 23:59:52', '0000-00-00 00:00:00', '-1', ''),
('24', '4', 'jgpELhbbe6moNadJVR41eJOUxSSbz-_t', '2016-05-29 00:23:45', '0000-00-00 00:00:00', '-1', ''),
('25', '1', 'Y-6k6ojVvo3xjtYFjt8vfBK8j_4ReRqv', '2016-05-30 07:27:36', '0000-00-00 00:00:00', '-1', ''),
('26', '1', 'e_cCTOrNHfZzon8CJb_-USOCX8VeB1O4', '2016-05-31 21:37:14', '0000-00-00 00:00:00', '-1', ''),
('27', '1', 'vX0LZS1n_iqbhixsAHKBehPaRt9jAA3h', '2016-06-01 18:22:58', '0000-00-00 00:00:00', '-1', ''),
('28', '4', 'zLrz_PBDBFI5ExQRiAHb7v4VPqZO02u8', '2016-06-02 06:54:21', '0000-00-00 00:00:00', '-1', ''),
('29', '1', 'v4s6c2f74Z1Xjh8B_4iQ6k04ENiWSy4Z', '2016-06-02 06:59:08', '0000-00-00 00:00:00', '-1', ''),
('30', '8', '_YAD_kxG398xf49RhMyFpM5WItSnmLXE', '2016-06-02 08:23:40', '0000-00-00 00:00:00', '-1', ''),
('31', '1', '7M66yoizzPuyqS89mmL7Xl1xHG8Ij-AB', '2016-06-02 09:01:39', '0000-00-00 00:00:00', '-1', ''),
('32', '9', 'JBBrPCQAP4xKzAIRQJN4OpdL7b8pKyGd', '2016-06-02 09:03:20', '0000-00-00 00:00:00', '-1', ''),
('33', '4', 'iBl-Y43OqGcL7oiILN3-wyo1vFl0lH6M', '2016-06-02 21:55:43', '0000-00-00 00:00:00', '-1', ''),
('34', '1', 'D56fUkqPJOHyIeitYp28cc-9fF9uGP7s', '2016-06-02 22:07:23', '0000-00-00 00:00:00', '-1', '');

-- ------------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(32) NOT NULL,
  `prenom` varchar(32) NOT NULL,
  `login` varchar(50) DEFAULT NULL,
  `password` varchar(40) NOT NULL,
  `email` varchar(50) NOT NULL,
  `status` enum('ADMIN','USER','LIVREUR','ADMIN_RESTAURANT','RESTAURANT') NOT NULL DEFAULT 'USER',
  `inscription_token` text,
  `is_login` tinyint(1) NOT NULL DEFAULT '0',
  `is_enable` tinyint(1) NOT NULL DEFAULT '0',
  `is_premium` tinyint(1) NOT NULL DEFAULT '0',
  `solde` double DEFAULT '0',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_confirmation` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_suppression` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`),
  UNIQUE KEY `login` (`login`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`uid`, `nom`, `prenom`, `login`, `password`, `email`, `status`, `inscription_token`, `is_login`, `is_enable`, `is_premium`, `solde`, `deleted`, `date_creation`, `date_confirmation`, `date_suppression`) VALUES
('1', 'admin', 'admin', 'admin@homemenus.fr', 'd033e22ae348aeb5660fc2140aec35850c4da997', 'admin@homemenus.fr', 'ADMIN', '', '1', '1', '0', '0', '0', '2016-05-15 20:37:34', '2016-05-15 20:37:34', '0000-00-00 00:00:00'),
('2', 'user1', 'user1', 'user1', 'db64ed30dee6eec931041275a02b158a466684f5', 'user1@resto.fr', 'RESTAURANT', '0Rud5I5YufnowgIMBwFT3Wu0-yzJATiF', '1', '1', '0', '0', '0', '2016-05-08 07:47:20', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
('3', 'fiari', 'mike', 'mfiari', 'db64ed30dee6eec931041275a02b158a466684f5', 'mfiari@mfiari.fr', 'LIVREUR', 'QBt1OUy3jpVGUqCz4cH5TkTt7LyG2wYW', '1', '1', '0', '0', '0', '2016-05-10 17:19:23', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
('4', 'fiari', 'mike', 'fiarimike@yahoo.fr', 'db64ed30dee6eec931041275a02b158a466684f5', 'fiarimike@yahoo.fr', 'USER', 'ffNWz9B_URB_477rGKBH0b67m76H-Ot4', '1', '1', '0', '0', '0', '2016-05-11 19:43:06', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
('5', 'admin', 'admin', 'admin_mac_pizza', 'db64ed30dee6eec931041275a02b158a466684f5', 'admin@macpizza.fr', 'ADMIN_RESTAURANT', '7knrU8p1cJWdeUfgBPfeGHMVVn3ezEb9', '1', '1', '0', '0', '0', '2016-05-17 19:55:17', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
('6', 'fiari', 'mike', 'fiarimike@gmail.com', 'db64ed30dee6eec931041275a02b158a466684f5', 'fiarimike@gmail.com', 'USER', 'tnAX9hGc2lr_gEpMgnWP8gjgbcsgFojP', '0', '0', '0', '0', '0', '2016-05-20 08:12:31', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
('7', 'fiari', 'mike', 'fiarimike2@yahoo.fr', 'db64ed30dee6eec931041275a02b158a466684f5', 'fiarimike2@yahoo.fr', 'USER', '-0hFiP_DJgjmN80uZSj3uolD4zSp6Abs', '0', '0', '0', '0', '0', '2016-05-20 08:13:27', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
('8', 'user', 'user', 'tajmahal_user', 'ba922b198b94fc35a6f3f8088fea4d62c976752c', 'tajmahal@yahoo.fr', 'RESTAURANT', 'VuuQk5bYjgv-84LbB3nG-znaYjJEndkE', '1', '1', '0', '0', '0', '2016-06-02 08:22:50', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
('9', 'admin', 'admin', 'tajmahal_admin', '400f76929b73086dbbc7873bbbedcedd445772c0', 'tajmahal@yahoo.fr', 'ADMIN_RESTAURANT', 'FyEi6Sqb712ay4GluQTjdP9_PsGurCIn', '1', '1', '0', '0', '0', '2016-06-02 09:02:34', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
