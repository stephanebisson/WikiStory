<?php

namespace MediaWiki\Extension\WikiStory;

class StoryContentHandler extends \JsonContentHandler {

	public function __construct( $modelId = 'story' ) {
		parent::__construct( $modelId );
	}

	protected function getContentClass() {
		return StoryContent::class;
	}

	public function getActionOverrides() {
		return [
			'edit' => StoryEditAction::class,
			'submit' => StorySubmitAction::class,
			'amp' => ViewAmpStoryAction::class,
		];
	}
}
