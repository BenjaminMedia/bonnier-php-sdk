<?php

namespace Bonnier\IndexSearch;

interface IServiceEventListener {

	public function onCreateCollection();

	public function onCreateItem();

}