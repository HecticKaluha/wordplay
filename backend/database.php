<?php
R::setup('mysql:host=localhost;dbname='. DB_NAME, DB_USER, DB_PASS);
R::useFeatureSet( 'novice/latest' );