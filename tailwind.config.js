// tailwind.config.js
module.exports = {
  content: [
    './resources/js/**/*.js',      // Include your JS files
    './views/**/*.php',            // Adjust for views folder
    './public/**/*.html',          // Include public HTML files if needed
  ],
  theme: {
    extend: {
      colors: {
        primary: '#1E3A8A',     // Primary blue
        secondary: '#4F46E5',   // Secondary indigo
        accent: '#10B981',      // Accent green
        muted: '#9CA3AF',       // Muted gray
        lightGray: '#F3F4F6',   // Light gray for backgrounds
        info: '#60A5FA',        // Info blue
        warning: '#FBBF24',     // Warning yellow
        success: '#34D399',     // Success green
        danger: '#EF4444',      // Danger red
      },
      boxShadow: {
        'inner-lg': 'inset 0 4px 6px rgba(0, 0, 0, 0.1)',   // Large inner shadow
        'glow': '0 4px 12px rgba(31, 41, 55, 0.25)',        // Glow shadow
      },
      fontFamily: {
        sans: ['Roboto', 'sans-serif'],    // Custom font family (Roboto)
      },
      spacing: {
        '18': '4.5rem',      // Custom spacing size
        '22': '5.5rem',
      },
      borderRadius: {
        'xl': '1.25rem',     // Custom large border radius
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),      // Forms plugin
    require('@tailwindcss/typography'), // Typography plugin
    require('@tailwindcss/aspect-ratio'), // Aspect-ratio plugin
  ],
  safelist: [
    'bg-primary', 'text-secondary', 'shadow-glow', // Include any dynamically generated classes to avoid purge
  ],
  corePlugins: {
    preflight: true, // Ensures base styles like reset.css are included
  },
};
