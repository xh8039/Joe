import { nodeResolve } from '@rollup/plugin-node-resolve';
import { terser } from 'rollup-plugin-terser';
export default {
	input: './rollup/codemirror.js',
	output: {
		file: './dist/codemirror.js',
		format: 'iife',
		inlineDynamicImports: true,
		sourcemap: true, // Consider adding sourcemaps for easier debugging
		minify: true
	},
	plugins: [nodeResolve(), terser({ compress: { drop_console: true } })]
};
