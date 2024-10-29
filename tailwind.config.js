module.exports = {
	content: [
		"./resources/**/*.{php,js}",
		"./Functionality/**/*.php",
		"./Components/**/*.php",
	],
	safelist: ["pb-right-0", "pb-left-0"],
	theme: {
		extend: {
			colors: {},
		},
	},
	plugins: [],
	important: true,
	prefix: "pb-",
	corePlugins: {
		preflight: false,
	},
};
