<?php

namespace MediaWiki\Extension\WikiStory;

class StorySubmitAction extends StoryEditAction {

	public function getName() {
		return 'submit';
	}

	public function show() {
		parent::show();
	}
}
