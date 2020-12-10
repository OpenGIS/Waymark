module.exports = function(grunt) {
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
		
		less: {
			wp_css: {
				files: {
					'assets/css/shared.css': 'assets/less/shared.less',
					'assets/css/front.css': 'assets/less/front.less',
					'assets/css/admin.css': 'assets/less/admin.less'
				}
			},
			js_css: {
				files: {
					'waymark-js/src/css/Waymark_Map.css': 'waymark-js/src/less/Waymark_Map.less',
					'waymark-js/src/css/Waymark_Map_Viewer.css': 'waymark-js/src/less/Waymark_Map_Viewer.less',
					'waymark-js/src/css/Waymark_Map_Editor.css': 'waymark-js/src/less/Waymark_Map_Editor.less'															
				}
			}			
		},
		
		concat: {
			js_js: {
				src: [
					'waymark-js/libs/js/leaflet.min.js',
					'waymark-js/libs/js/*',						
					'waymark-js/src/js/Waymark_Map.js',
					'waymark-js/src/js/Waymark_Map_Viewer.js',
					'waymark-js/src/js/Waymark_Map_Editor.js',
					'waymark-js/src/js/Waymark_Map_Factory.js'																	
				],
				dest: 'waymark-js/dist/js/waymark-js.js'				
			},
			js_css: {
				src: [
					'waymark-js/libs/css/*.css',
					'waymark-js/src/css/*.css'
				],
				dest: 'waymark-js/dist/css/waymark-js.css'
			},
			wp_css: {
				files: {
					'assets/css/front.css': ['assets/css/shared.css', 'assets/css/front.css'],
					'assets/css/admin.css': ['assets/css/shared.css', 'assets/css/admin.css'],					
				}
			},
			wp_js: {
				files: {
					'assets/js/front.min.js': ['assets/js/shared.js', 'assets/js/front.js'],
					'assets/js/admin.min.js': ['assets/js/shared.js', 'assets/js/admin.js'],					
				}
			}			
		},	
		
		terser: {
			js_js: {
				files: {
					'waymark-js/dist/js/waymark-js.min.js': ['waymark-js/dist/js/waymark-js.js']
				}
			},
			wp_js: {
				files: {
					'assets/js/front.min.js': ['assets/js/front.min.js'],
					'assets/js/admin.min.js': ['assets/js/admin.min.js']					
				}
			}			
		},
		
		cssmin: {
			wp_css: {
				files: {
					'assets/css/front.min.css': 'assets/css/front.css',
					'assets/css/admin.min.css': 'assets/css/admin.css'
				}
			},
			js_css: {
				files: {
					'waymark-js/dist/css/waymark-js.min.css': 'waymark-js/dist/css/waymark-js.css'					
				}
			}			
		},
		
		copy: {
			js_js: {
				expand: true,
				cwd: 'waymark-js/dist/js',
				src: '**',
				dest: 'assets/dist/waymark-js/js'
			},
			js_css: {
				expand: true,
				cwd: 'waymark-js/dist/css',
				src: '**',
				dest: 'assets/dist/waymark-js/css'
			}
			
		},		
		
		watch: {
			js_js: {
				files: ['waymark-js/src/js/*.js'],
				tasks: ['build_js_js']
			},
			js_css: {
				files: ['waymark-js/src/less/*.less'],
				tasks: ['build_js_css']
			},				
			wp_css: {
				files: ['assets/less/*.less'],
				tasks: ['build_wp_css']
			},
			wp_js: {
				files: ['assets/js/*.js'],
				tasks: ['build_wp_js']
			},
			wp_readme: {
				files: ['readme.txt'],
				tasks: ['build_wp_md']
			}			
		},

		wp_readme_to_markdown: {
			build_wp_md: {
				files: {
					'readme.md': 'readme.txt'
				},
				options: {
					screenshot_url: 'https://ps.w.org/{plugin}/assets/{screenshot}.jpg',
					post_convert: function(content) {
						//Remove unsupported Vimeo tags
						return content.replace(/\[vimeo(.*)\]\n*/g, '');
					}
				}				
			}
		}
  });

 	grunt.loadNpmTasks('grunt-terser');
  grunt.loadNpmTasks('grunt-contrib-concat');  
	grunt.loadNpmTasks('grunt-contrib-cssmin');  
	grunt.loadNpmTasks('grunt-contrib-less');  
 	grunt.loadNpmTasks('grunt-contrib-copy');	
  grunt.loadNpmTasks('grunt-contrib-watch');	
	grunt.loadNpmTasks('grunt-wp-readme-to-markdown');

  grunt.registerTask('default', [
  	'less',
   	'concat',
 		'terser',
  	'cssmin',
   	'copy',
  	'watch',
  	'wp_readme_to_markdown'
  ]);

  grunt.registerTask('build_js_js', [
   	'concat:js_js',
 		'terser:js_js',
   	'copy:js_js'
  ]); 

  grunt.registerTask('build_js_css', [
   	'less:js_css',
 		'concat:js_css',
   	'cssmin:js_css',
   	'copy:js_css'
  ]); 

  grunt.registerTask('build_wp_css', [
   	'less:wp_css',
 		'concat:wp_css',   	
   	'cssmin:wp_css'
  ]); 

  grunt.registerTask('build_wp_js', [
 		'concat:wp_js',
   	'terser:wp_js'
  ]);        

  grunt.registerTask('build_wp_md', [
 		'wp_readme_to_markdown:build_wp_md'
  ]);     
};
