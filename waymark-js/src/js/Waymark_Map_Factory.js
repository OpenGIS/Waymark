/*
	==================================
	============= FACTORY ============
	==================================
*/

function Waymark_Map_Factory() {
	this.instances = [];

	this.viewer = function () {
		var instance = Object.assign(new Waymark_Map(), new Waymark_Map_Viewer());

		this.instances.push(instance);

		return instance;
	};

	this.editor = function () {
		var instance = Object.assign(new Waymark_Map(), new Waymark_Map_Editor());

		this.instances.push(instance);

		return instance;
	};
}

window.Waymark_Map_Factory = new Waymark_Map_Factory();
