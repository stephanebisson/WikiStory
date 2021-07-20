<?php

namespace MediaWiki\Extension\WikiStory;

use Html;

class StoryContent extends \JsonContent {

	public function __construct( $text, $modelId = 'story' ) {
		parent::__construct( $text, $modelId );
	}

	public function getName() {
		$story = $this->getData()->getValue();
		return $story->name;
	}

	public function getFrames() {
		$story = $this->getData()->getValue();
		return $story->frames ?? [];
	}

	private function framesAreValid() {
		$frames = $this->getFrames();
		if ( count( $frames ) > 0 ) {
			foreach ( $frames as $frame ) {
				if ( empty( $frame->img ) || empty( $frame->text ) ) {
					return false;
				}
			}
			return true;
		}
		return false;
	}

	public function isValid() {
		$validJson = parent::isValid();
		return $validJson && !empty( $this->getName() ) && $this->framesAreValid();
	}

	public function toAmpStory() {
		$frames = [];
		$i = 0;
		foreach ( $this->getFrames() as $frame ) {
			$frames[] = Html::rawElement(
				'amp-story-page',
				[ 'id' => $i, 'auto-advance-after' => '2s' ],
				Html::rawElement(
					'amp-story-grid-layer',
					[ 'template' => 'fill' ],
					Html::rawElement(
						'amp-img',
						[ 'src' => $frame->img, 'width' => 500, 'layout' => 'fill' ]
					) .
					Html::element('q', [], $frame->text)
				)
			);
			$i++;
		}
		$s1 = Html::element(
			'script',
			[
				'async' => '',
				'src' => 'https://cdn.ampproject.org/v0.js',
			]);
		$s2 = Html::element(
			'script',
			[
				'async' => '',
				'custom-element' => 'amp-story',
				'src' => 'https://cdn.ampproject.org/v0/amp-story-1.0.js',
			]);
		$ampStory = Html::rawElement(
			'amp-story',
			[ 'standalone' => '', 'title' => $this->getName(), 'publisher' => 'WMF' ],
			implode( "\n", $frames )
		);
		$meta = Html::element('meta', ['charset' => 'utf-8']); // <meta charset="utf-8">
		$link = Html::element('link', ['rel' => 'canonical', 'href' => 'Story:Cat?action=amp']); //<link rel="canonical" href="page/url">
		$vp = Html::element('meta', ['name' => 'viewport', 'content' => 'width=device-width']); // <meta name="viewport" content="width=device-width">
		$boilerplate = '<style amp-boilerplate>body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style><noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>';
		$head = Html::rawElement('head', [], $meta . $s1 . $s2 . $link . $vp . $boilerplate);
		$body = Html::rawElement('body', [], $ampStory);
		$html = Html::rawElement('html', ['⚡' => ''], $head . $body);
		$doctype = '<!doctype html>';
		return $doctype . $html;
	}

	protected function fillParserOutput( \Title $title, $revId,
			\ParserOptions $options, $generateHtml, \ParserOutput &$output
		) {
//		$output->addModules( 'mw.ext.story.view' );
//		$output->addJsConfigVars( 'story', $this->getData()->getValue() );
//		$html = '<div class="story-view-app"></div>';
//		$html .= '<div class="story-view">';
//		$html .= "<strong>" . $this->getName() . "</strong><br>";
//		foreach ($this->getFrames() as $frame) {
//			$html .= "<img src=\"" . $frame->img . "\" width=\"500px\" />";
//			$html .= "<div>" . $frame->text . "</div>";
//		}
//		$html .= '</div>';


		$output->addModules( 'mw.ext.story.ampplayer' );
		$html = Html::rawElement(
			'amp-story-player',
			[
				'style' => 'width: 370px; height: 622px;',
//				'layout' => "responsive",
//				'width' => 360,
//				'height' => 600
			],
			implode( "\n", [
				Html::rawElement('script', ['type' => 'application/json'], '{"behavior": {"autoplay": true}}'),
				Html::element( 'a', [ 'href' => '/wiki/Story:Cat?action=amp' ], 'Cat story...' ),
			] )
		);
		$output->setText( $html );
	}
}
