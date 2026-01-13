# 🧹 Schwäbisch Broom Shop

**A modern, full-stack e-commerce web application for premium handcrafted brooms**

Built with Next.js 16, TypeScript, Prisma, and SQLite - ready to run locally in minutes!

---

## 🚀 Quick Start

Get the application running in 3 simple steps:

```bash
# 1. Install dependencies
npm install

# 2. Setup database
npx prisma db push
npm run prisma:seed

# 3. Start development server
npm run dev
```

Open [http://localhost:3000](http://localhost:3000) in your browser and start shopping!

---

## 🎓 Teacher Credentials

Use these credentials to test the application:

- **Email:** `teacher@example.com`
- **Password:** `lehrerpass`

---

## ✨ Features

### 🎨 Modern User Interface
- Beautiful, responsive design with smooth animations
- Gradient hero section with eye-catching typography
- Product cards with hover effects and image zoom
- Mobile-first, fully responsive layout
- Clean navigation with sticky header

### 🔐 Secure Authentication
- JWT-based authentication with HttpOnly cookies
- Bcrypt password hashing (10 rounds)
- Protected routes and API endpoints
- User registration and login

### 🛒 Complete Shopping Experience
- Browse premium broom products with high-quality images
- Product detail pages with SKU and descriptions
- Shopping cart with localStorage persistence
- Quantity management and cart updates
- Real-time cart total calculation

### 💳 Checkout & Orders
- Secure checkout flow with shipping information
- **Idempotent order processing** (prevents duplicate orders)
- Fake payment method for testing (`fake_card`)
- Order confirmation page
- Downloadable JSON receipts
- Order tracking by order number

### 🗄️ Database & Data
- SQLite database for simplicity
- Prisma ORM for type-safe database access
- Pre-seeded with 10 premium broom products
- Teacher account ready to use

---

## 🛠️ Technology Stack

| Category | Technologies |
|----------|-------------|
| **Frontend** | Next.js 16 (App Router), React 19, TypeScript |
| **Styling** | Tailwind CSS, shadcn/ui Components |
| **Backend** | Next.js API Routes, Prisma ORM |
| **Database** | SQLite |
| **Authentication** | JWT, bcrypt, HttpOnly Cookies |
| **Icons** | Lucide React |
| **Images** | Pexels (via CDN) |

---

## 📁 Project Structure

```
charliesschockoladenfabrik/
├── app/                          # Next.js App Router
│   ├── api/                      # API Routes
│   │   ├── auth/                 # Authentication endpoints
│   │   │   ├── login/route.ts
│   │   │   └── register/route.ts
│   │   ├── cart/
│   │   │   └── checkout/route.ts # Idempotent checkout
│   │   ├── orders/
│   │   │   └── [orderNumber]/route.ts
│   │   └── products/route.ts
│   ├── cart/page.tsx             # Shopping cart
│   ├── checkout/page.tsx         # Checkout form
│   ├── login/page.tsx            # Login page
│   ├── orders/[orderNumber]/page.tsx  # Order confirmation
│   ├── products/[id]/page.tsx    # Product detail
│   ├── register/page.tsx         # Registration
│   ├── layout.tsx                # Root layout with nav & footer
│   ├── page.tsx                  # Homepage with hero & products
│   └── globals.css               # Global styles & animations
├── components/ui/                # Reusable UI components
│   ├── button.tsx
│   ├── card.tsx
│   ├── input.tsx
│   └── label.tsx
├── lib/                          # Utility libraries
│   ├── auth.ts                   # JWT helpers & middleware
│   ├── prisma.ts                 # Prisma client singleton
│   └── utils.ts                  # General utilities
├── prisma/                       # Database
│   ├── schema.prisma             # Database schema
│   ├── seed.ts                   # Seed script
│   └── dev.db                    # SQLite database (generated)
├── .env                          # Environment variables
├── .env.example                  # Example environment file
├── next.config.ts                # Next.js configuration
├── tailwind.config.ts            # Tailwind configuration
├── tsconfig.json                 # TypeScript configuration
├── package.json                  # Dependencies & scripts
├── DOCS.md                       # Technical documentation
└── README.md                     # This file
```

---

## 📖 Detailed Setup Guide

### Prerequisites

- **Node.js**: v18.0.0 - v20.x (required)
- **npm** or **pnpm**: Latest version
- **Git**: For cloning the repository

### Step 1: Clone the Repository

```bash
git clone <repository-url>
cd charliesschockoladenfabrik
```

### Step 2: Install Dependencies

```bash
npm install
```

This installs all required packages including Next.js 16, Prisma, React, and more.

### Step 3: Configure Environment

```bash
cp .env.example .env
```

The `.env` file contains:
```
DATABASE_URL="file:./prisma/dev.db"
JWT_SECRET="your-secret-key-change-this-in-production"
```

> **Note:** The default `.env` values work out of the box for local development.

### Step 4: Setup Database

```bash
# Push schema to create database
npx prisma db push

# Seed with teacher account and products
npm run prisma:seed
```

This creates:
- ✅ SQLite database at `prisma/dev.db`
- ✅ Teacher account: `teacher@example.com` / `lehrerpass`
- ✅ 10 premium broom products with images

### Step 5: Start Development Server

```bash
npm run dev
```

The application will be available at [http://localhost:3000](http://localhost:3000)

---

## 🎯 Usage Guide

### For Teachers

1. **Login** with `teacher@example.com` / `lehrerpass`
2. **Browse products** on the homepage
3. **Add items** to cart by clicking "Details ansehen" → "In den Warenkorb"
4. **View cart** and adjust quantities
5. **Checkout** with shipping information
6. **Complete order** using the `fake_card` payment method
7. **View order confirmation** and download receipt

### For Students (Register New Account)

1. Click **"Registrieren"** in the navigation
2. Enter email, optional name, and password
3. Click **"Registrieren"** to create account
4. You'll be automatically logged in
5. Start shopping!

---

## 🔧 Available Scripts

| Command | Description |
|---------|-------------|
| `npm run dev` | Start development server (http://localhost:3000) |
| `npm run build` | Build for production |
| `npm run start` | Start production server |
| `npm run lint` | Run ESLint |
| `npm run prisma:push` | Push Prisma schema to database |
| `npm run prisma:seed` | Seed database with initial data |
| `npm run prisma:studio` | Open Prisma Studio (database GUI) |

---

## 🧪 Testing the Application

### Manual Testing Checklist

1. **Homepage**
   - [ ] Hero section displays correctly
   - [ ] Products grid displays correctly
   - [ ] Product cards show price and description
   - [ ] Hover effects work on product cards

2. **Authentication**
   - [ ] Can register new account
   - [ ] Can login with teacher credentials
   - [ ] User email shows in navigation
   - [ ] Cannot access checkout without login

3. **Product Detail**
   - [ ] Product images load from Pexels
   - [ ] Can adjust quantity
   - [ ] "Add to cart" redirects to cart page

4. **Shopping Cart**
   - [ ] Products appear in cart
   - [ ] Can update quantities
   - [ ] Can remove items
   - [ ] Total calculates correctly
   - [ ] Cart persists in localStorage

5. **Checkout**
   - [ ] Shipping form validates inputs
   - [ ] Only `fake_card` payment is accepted
   - [ ] Order processes successfully
   - [ ] Redirects to order confirmation

6. **Order Confirmation**
   - [ ] Order details display correctly
   - [ ] Can download JSON receipt
   - [ ] "Continue shopping" returns to homepage

### Testing Idempotency

Test that duplicate orders are prevented:

1. Complete a checkout
2. Use browser DevTools to find the `checkoutToken` in the request
3. Send the same request again (same token)
4. Verify you get a `409 Conflict` response
5. Verify NO duplicate order was created

---

## 🎨 Design System

### Color Palette

- **Primary (Green):** Forest green accents with professional styling
- **Background:** `hsl(0, 0%, 100%)` - Clean white
- **Foreground:** Dark text for readability
- **Muted:** Subtle backgrounds
- **Border:** Light borders

### Typography

- **Font Family:** Inter (Google Fonts)
- **Headings:** Bold, tracking-tight
- **Body:** Regular, antialiased

### Components

All UI components are built with shadcn/ui and Tailwind CSS:
- **Button:** Multiple variants (default, outline, ghost)
- **Card:** Product cards, content cards
- **Input:** Form inputs with focus states
- **Label:** Form labels

---

## 📚 API Documentation

### Authentication

**POST `/api/auth/register`**
- Creates new user account
- Request: `{ email, password, name? }`
- Response: User object + auth cookie

**POST `/api/auth/login`**
- Authenticates user
- Request: `{ email, password }`
- Response: User object + auth cookie

### Products

**GET `/api/products`**
- Returns all broom products
- No authentication required

### Checkout

**POST `/api/cart/checkout`**
- Creates order (idempotent!)
- Requires authentication
- Request: `{ checkoutToken, paymentMethod, shipping, items[] }`
- Response: Order object

**GET `/api/orders/[orderNumber]`**
- Gets order by order number
- Requires authentication
- Validates order ownership

> 📖 For detailed API documentation, see [DOCS.md](./DOCS.md)

---

## 🔒 Security Features

### Password Security
- Bcrypt hashing with 10 rounds
- Never stored in plain text

### JWT Authentication
- HttpOnly cookies prevent XSS attacks
- 7-day token expiration
- Secure flag in production

### Database Security
- Unique constraints on email, SKU, order numbers
- Prisma ORM prevents SQL injection
- Input validation on all endpoints

### Order Security
- Idempotent checkout prevents duplicates
- Database transactions ensure atomicity
- User ownership validation on orders

---

## 🐛 Troubleshooting

### Build Errors

**Error: `Cannot find module`**
```bash
rm -rf node_modules package-lock.json
npm install
```

**Error: `Prisma Client not generated`**
```bash
npx prisma generate
```

### Runtime Errors

**Error: `Database not found`**
```bash
npx prisma db push
npm run prisma:seed
```

**Error: `Images not loading`**
- Check internet connection (images from Pexels)
- Verify `next.config.ts` allows `images.pexels.com`

**Error: `Login fails with correct credentials`**
- Clear browser cookies
- Check `.env` has `JWT_SECRET`
- Verify database was seeded: `npm run prisma:seed`

**Error: `Node version incompatible`**
- Use Node.js 18-20
- Check version: `node --version`
- Update if needed: [nodejs.org](https://nodejs.org)

---

## 📦 Database

### Models

- **User:** Authentication and orders
- **Product:** Premium broom products
- **Order:** Customer orders with shipping
- **OrderItem:** Line items in orders

### View Database

```bash
npm run prisma:studio
```

Opens Prisma Studio at [http://localhost:5555](http://localhost:5555)

### Reset Database

```bash
rm prisma/dev.db
npx prisma db push
npm run prisma:seed
```

---

## 🚢 Deployment

### Production Build

```bash
# Build the application
npm run build

# Start production server
npm run start
```

### Environment Variables

For production, update `.env`:

```
DATABASE_URL="file:./prisma/prod.db"
JWT_SECRET="<strong-random-secret-min-32-chars>"
NODE_ENV="production"
```

### Deployment Checklist

- [ ] Set strong `JWT_SECRET` (use password generator)
- [ ] Run `npm run build` successfully
- [ ] Test all flows in production mode
- [ ] Database seeded with products
- [ ] Configure hosting (Vercel, Railway, etc.)

---

## 📄 License

This is a school project for educational purposes.

---

## 🤝 Contributing

This is a school project, but improvements are welcome:

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

---

## 📞 Support

- **Technical Docs:** See [DOCS.md](./DOCS.md)
- **Issues:** Check codebase for inline comments
- **Questions:** Review this README and documentation

---

## 🎓 Learning Resources

### Next.js 16
- [Official Docs](https://nextjs.org/docs)
- [App Router Guide](https://nextjs.org/docs/app)

### Prisma
- [Prisma Docs](https://www.prisma.io/docs)
- [Prisma Quickstart](https://www.prisma.io/docs/getting-started/quickstart)

### Tailwind CSS
- [Tailwind Docs](https://tailwindcss.com/docs)
- [Tailwind UI](https://tailwindui.com/)

---

**Built with ❤️ for learning and education**

**Version:** 1.0.0  
**Next.js:** 16.1.1  
**Last Updated:** January 13, 2026
