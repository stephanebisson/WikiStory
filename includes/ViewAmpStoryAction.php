<?php


namespace MediaWiki\Extension\WikiStory;

use ViewAction;

class ViewAmpStoryAction extends ViewAction {

	public function getName() {
		return 'amp';
	}

	public function show() {
		$out = $this->getOutput();
		$out->allowClickjacking();
		$out->setArticleBodyOnly(true);
		/** @var StoryContent $story */
		$story = $this->getWikiPage()->getContent();
		$out->addHTML( $story->toAmpStory() );
	}
}