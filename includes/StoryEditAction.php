<?php

namespace MediaWiki\Extension\WikiStory;

class StoryEditAction extends \EditAction {

	public function show() {
		$this->useTransactionalTimeLimit();
		$editPage = new StoryEditPage( $this->page );
		$editPage->setContextTitle( $this->getTitle() );
		$editPage->edit();
	}

}
