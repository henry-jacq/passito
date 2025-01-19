// tailwind.config.js
const plugin = require('tailwindcss/plugin');

module.exports = {
	content: [
		'./resources/js/**/*.js',      // Include your JS files
		'./views/**/*.php',            // Adjust for views folder
		'./public/**/*.html',          // Include public HTML files if needed
	],
	theme: {
		extend: {
			colors: {
				primary: '#1E3A8A',         // Primary blue
				secondary: '#4F46E5',       // Secondary indigo
				accent: '#10B981',          // Accent green
				muted: '#9CA3AF',           // Muted gray
				lightGray: '#F3F4F6',       // Light gray for backgrounds
				info: '#60A5FA',            // Info blue
				warning: '#FBBF24',         // Warning yellow
				success: '#34D399',         // Success green
				danger: '#EF4444',          // Danger red
				dark: '#1F2937',            // Dark gray for header/footer
				lightAccent: '#D1FAE5',     // Light version of accent green
				darkPrimary: '#1E293B',     // Dark mode primary
				darkSecondary: '#64748B',   // Dark mode secondary
				wardenPrimary: '#0EA5E9',   // Custom for wardens
				superAdminPrimary: '#9333EA', // Custom for super-admin
			},
			boxShadow: {
				'inner-lg': 'inset 0 4px 6px rgba(0, 0, 0, 0.1)',   // Large inner shadow
				'glow': '0 4px 12px rgba(31, 41, 55, 0.25)',        // Glow shadow
				'soft-xl': '0 8px 20px rgba(31, 41, 55, 0.1)',      // Softer shadow for elements
			},
			fontFamily: {
				sans: ['Roboto', 'sans-serif'],        // Default sans font
				heading: ['Poppins', 'sans-serif'],    // For headings and titles
				display: ['Inter', 'serif'],    // For display/important text
				mono: ['Source Code Pro', 'monospace'],// Monospace for code
			},
			fontWeight: {
				light: '300',
				normal: '400',
				medium: '500',
				semibold: '600',
				bold: '700',
				extrabold: '800',
			},
			fontSize: {
				'xs': '.75rem',      // Small text
				'sm': '.875rem',     // Slightly bigger small text
				'base': '1rem',      // Default size (16px)
				'lg': '1.125rem',    // Larger text
				'xl': '1.25rem',     // Extra large text
				'2xl': '1.5rem',     // 2x larger text
				'3xl': '1.875rem',   // 3x larger text
				'4xl': '2.25rem',    // 4x larger text
				'5xl': '3rem',       // 5x larger text
				'6xl': '3.75rem',    // 6x larger text
			},
			spacing: {
				'18': '4.5rem',      // Custom spacing size
				'22': '5.5rem',
				'72': '18rem',       // Extra large spacing for cards
				'84': '21rem',
				'88': '22rem',
				'96': '24rem',
				'104': '26rem',
			},
			borderRadius: {
				'xl': '1.25rem',     // Custom large border radius
				'2xl': '1.75rem',    // Even larger for cards or containers
			},
			animation: {
				fadeIn: 'fadeIn 0.5s ease-in-out',
				fadeOut: 'fadeOut 0.5s ease-in-out',
				slideIn: 'slideIn 0.5s ease-out',
				slideOut: 'slideOut 0.5s ease-out',
			},
			keyframes: {
				fadeIn: {
					'0%': { opacity: '0' },
					'100%': { opacity: '1' },
				},
				slideIn: {
					'0%': { transform: 'translateX(-100%)' },
					'100%': { transform: 'translateX(0)' },
				},
			},
			typography: {
				admin: {
					css: {
						color: '#1E3A8A',
						a: {
							color: '#4F46E5',
							'&:hover': { color: '#10B981' },
						},
					},
				},
			},
		},
	},
	plugins: [
		require('@tailwindcss/forms'),          // Forms plugin
		require('@tailwindcss/typography'),     // Typography plugin
		require('@tailwindcss/aspect-ratio'),   // Aspect-ratio plugin
		require('@tailwindcss/line-clamp'),     // Line clamp plugin for text truncation
		require('tailwind-scrollbar')({ nocompatible: true }), // Ensure compatibility mode is off
		require('tailwind-scrollbar-hide'),
	],
	safelist: [
		'bg-primary', 'text-secondary', 'shadow-glow',      // Include dynamically generated classes
		'font-heading', 'font-display',                    // Dynamic font classes
		'bg-success', 'bg-danger', 'text-warning',         // State-specific colors
		'border-accent', 'ring-info',                      // Borders and rings
		'scrollbar', 'scrollbar-hide',
	],
	corePlugins: {
		preflight: true, // Ensures base styles like reset.css are included
	},
};
