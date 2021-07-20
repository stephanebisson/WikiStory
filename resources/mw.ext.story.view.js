var Vue = require( 'vue' ).default || require( 'vue' );
var App = require( './App.vue' );
new Vue( {
	el: document.querySelector( '.story-view-app' ),
	render: function ( createElement ) {
		return createElement( App );
	}
});
