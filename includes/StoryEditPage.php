<?php

namespace MediaWiki\Extension\WikiStory;

use EditPage;
use OOUI\FieldLayout;
use OOUI\TextInputWidget;

class StoryEditPage extends EditPage {

	protected function showContentForm() {
		$out = $this->context->getOutput();

		/** @var StoryContent $story */
		$story = $this->getCurrentContent();

		$form = '<div class="story-editor">';
		$form .= new FieldLayout(
			new TextInputWidget( [ 'name' => 'story_name', 'value' => $story->getName() ] ),
			[ 'label' => 'Name', 'align' => 'left' ]
		);
		$i = 0;
		foreach ($story->getFrames() as $frame) {
			$form .= "<h3>Frame #$i</h3>";
			$form .= new FieldLayout(
				new TextInputWidget( [ 'name' => "story_frame_{$i}_img", 'value' => $frame->img ] ),
				[ 'label' => 'Image', 'align' => 'left' ]
			);
			$form .= new FieldLayout(
				new TextInputWidget( [ 'name' => "story_frame_{$i}_text", 'value' => $frame->text ] ),
				[ 'label' => 'Text', 'align' => 'left' ]
			);
			$i++;
		}
		$form .= "<h3>New Frame</h3>";
		$form .= new FieldLayout(
			new TextInputWidget( [ 'name' => "story_frame_{$i}_img", 'value' => '' ] ),
			[ 'label' => 'Image', 'align' => 'left' ]
		);
		$form .= new FieldLayout(
			new TextInputWidget( [ 'name' => "story_frame_{$i}_text", 'value' => '' ] ),
			[ 'label' => 'Text', 'align' => 'left' ]
		);
		$form .= '</div><br><br>';
		$out->enableOOUI();
		$out->addHTML( $form );
	}

	protected function importContentFormData( &$request ) {
		$story = [ 'name' => $request->getText("story_name"), 'frames' => [] ];

		$i = 0;
		while ( true ) {
			$img = $request->getText("story_frame_{$i}_img");
			$text = $request->getText("story_frame_{$i}_text");
			if ( empty( $img ) && empty( $text ) ) {
				// stop reading as soon as both are empty
				break;
			}
			$story['frames'][] = [ 'img' => $img, 'text' => $text ];
			$i++;
		}

		return json_encode( $story, JSON_PRETTY_PRINT );
	}

}
