/*
	==================================
	============= FACTORY ============
	==================================
*/	

function Waymark_Map_Factory() {
	this.viewer = function() {	
		return Object.assign(new Waymark_Map, new Waymark_Map_Viewer);
	}

	this.editor = function() {	
		return Object.assign(new Waymark_Map, new Waymark_Map_Editor);
	}	
}

window.Waymark_Map_Factory = new Waymark_Map_Factory;
