<?php

namespace Bonnier;

interface IRestEventListener {

	public function onCreateCollection();

	public function onCreateItem();

}