export function ChocolateLogo({ className = "w-12 h-12" }: { className?: string }) {
  return (
    <svg
      viewBox="0 0 120 120"
      className={className}
      xmlns="http://www.w3.org/2000/svg"
    >
      <defs>
        <linearGradient id="handle-grad" x1="0%" y1="0%" x2="100%" y2="100%">
          <stop offset="0%" style={{ stopColor: '#8B4513', stopOpacity: 1 }} />
          <stop offset="100%" style={{ stopColor: '#D2691E', stopOpacity: 1 }} />
        </linearGradient>
        <linearGradient id="bristle-grad" x1="0%" y1="0%" x2="0%" y2="100%">
          <stop offset="0%" style={{ stopColor: '#FFD700', stopOpacity: 1 }} />
          <stop offset="100%" style={{ stopColor: '#DAA520', stopOpacity: 1 }} />
        </linearGradient>
        <radialGradient id="glow-gold">
          <stop offset="0%" style={{ stopColor: '#FFD700', stopOpacity: 0.8 }} />
          <stop offset="100%" style={{ stopColor: '#FFD700', stopOpacity: 0 }} />
        </radialGradient>
      </defs>
      
      <circle cx="60" cy="60" r="56" fill="#000000" opacity="0.05"/>
      
      <ellipse cx="60" cy="100" rx="35" ry="8" fill="#000000" opacity="0.15"/>
      
      <rect x="52" y="25" width="16" height="50" rx="8" fill="url(#handle-grad)" stroke="#654321" strokeWidth="1.5"/>
      
      <ellipse cx="60" cy="27" rx="9" ry="5" fill="#A0522D"/>
      <ellipse cx="60" cy="26" rx="7" ry="3.5" fill="#CD853F"/>
      
      <g transform="translate(60, 70)">
        <path d="M -32 0 L -30 22 L -28 28 L -26 22 L -25 0 Z" fill="url(#bristle-grad)" opacity="0.9"/>
        <path d="M -24 0 L -22 24 L -20 30 L -18 24 L -17 0 Z" fill="url(#bristle-grad)"/>
        <path d="M -16 0 L -14 26 L -12 32 L -10 26 L -9 0 Z" fill="url(#bristle-grad)" opacity="0.95"/>
        <path d="M -8 0 L -6 28 L -4 34 L -2 28 L -1 0 Z" fill="url(#bristle-grad)"/>
        <path d="M 0 0 L 2 30 L 4 36 L 6 30 L 8 0 Z" fill="url(#bristle-grad)" opacity="1"/>
        <path d="M 9 0 L 11 28 L 13 34 L 15 28 L 16 0 Z" fill="url(#bristle-grad)"/>
        <path d="M 17 0 L 19 26 L 21 32 L 23 26 L 24 0 Z" fill="url(#bristle-grad)" opacity="0.95"/>
        <path d="M 25 0 L 27 24 L 29 30 L 31 24 L 32 0 Z" fill="url(#bristle-grad)" opacity="0.9"/>
      </g>
      
      <ellipse cx="60" cy="72" rx="34" ry="4" fill="#8B4513" opacity="0.6"/>
      
      <g transform="rotate(-8 60 60)">
        <circle cx="48" cy="48" r="5" fill="#FFFFFF" stroke="#333" strokeWidth="1.5"/>
        <circle cx="72" cy="48" r="5" fill="#FFFFFF" stroke="#333" strokeWidth="1.5"/>
        
        <circle cx="47" cy="47" r="3" fill="#000000"/>
        <circle cx="71" cy="47" r="3" fill="#000000"/>
        
        <circle cx="48.5" cy="46" r="1.2" fill="#FFFFFF"/>
        <circle cx="72.5" cy="46" r="1.2" fill="#FFFFFF"/>
        
        <path d="M 50 58 Q 60 64, 70 58" stroke="#DC143C" strokeWidth="2.5" fill="none" strokeLinecap="round"/>
        <path d="M 52 59 Q 60 63, 68 59" fill="#DC143C" opacity="0.3"/>
        
        <path d="M 42 52 Q 45 48, 48 52" stroke="#8B4513" strokeWidth="1.5" fill="none"/>
        <path d="M 72 52 Q 75 48, 78 52" stroke="#8B4513" strokeWidth="1.5" fill="none"/>
      </g>
      
      <circle cx="25" cy="25" r="10" fill="url(#glow-gold)" opacity="0.6">
        <animate attributeName="r" values="8;12;8" dur="2s" repeatCount="indefinite"/>
        <animate attributeName="opacity" values="0.4;0.7;0.4" dur="2s" repeatCount="indefinite"/>
      </circle>
      <text x="25" y="31" fontSize="18" textAnchor="middle" fill="#FFD700" fontWeight="bold">✨</text>
      
      <circle cx="95" cy="85" r="8" fill="url(#glow-gold)" opacity="0.5">
        <animate attributeName="r" values="6;10;6" dur="2.3s" repeatCount="indefinite"/>
        <animate attributeName="opacity" values="0.3;0.6;0.3" dur="2.3s" repeatCount="indefinite"/>
      </circle>
      <text x="95" y="90" fontSize="14" textAnchor="middle" fill="#FFD700" fontWeight="bold">✨</text>
      
      <path d="M 85 15 L 87 20 L 92 21 L 88 25 L 89 30 L 85 27 L 81 30 L 82 25 L 78 21 L 83 20 Z" fill="#FFD700" opacity="0.8">
        <animateTransform attributeName="transform" type="rotate" from="0 85 22" to="360 85 22" dur="4s" repeatCount="indefinite"/>
      </path>
    </svg>
  );
}
