# Charlie's Schokoladenfabrik - Technical Documentation

## Table of Contents

1. [Project Overview](#project-overview)
2. [Architecture](#architecture)
3. [Technology Stack](#technology-stack)
4. [Database Schema](#database-schema)
5. [API Documentation](#api-documentation)
6. [Authentication Flow](#authentication-flow)
7. [Checkout Flow & Idempotency](#checkout-flow--idempotency)
8. [File Structure](#file-structure)
9. [Development Guide](#development-guide)
10. [Deployment](#deployment)

---

## Project Overview

Charlie's Schokoladenfabrik is a modern e-commerce web application for a school chocolate shop. Built with Next.js 16, it provides a complete shopping experience including product browsing, cart management, secure checkout, and order tracking.

### Key Features

- **Modern UI/UX**: Beautiful, responsive design with smooth animations
- **User Authentication**: JWT-based auth with HttpOnly cookies
- **Product Management**: Browse chocolate products with high-quality images
- **Shopping Cart**: Client-side cart with localStorage persistence
- **Secure Checkout**: Idempotent order processing with database transactions
- **Order Tracking**: View order history and download receipts
- **Fully Local**: Runs completely locally without external dependencies

---

## Architecture

### Application Architecture

```
┌─────────────┐
│   Browser   │
│  (Client)   │
└──────┬──────┘
       │
       │ HTTP/HTTPS
       │
┌──────▼──────────────────────────────┐
│      Next.js 16 Server              │
│  ┌────────────────────────────┐    │
│  │   App Router               │    │
│  │  - Server Components       │    │
│  │  - Client Components       │    │
│  │  - API Routes              │    │
│  └────────┬───────────────────┘    │
│           │                         │
│  ┌────────▼───────────────────┐    │
│  │  Authentication Layer      │    │
│  │  - JWT Verification        │    │
│  │  - Cookie Management       │    │
│  └────────┬───────────────────┘    │
│           │                         │
│  ┌────────▼───────────────────┐    │
│  │   Business Logic           │    │
│  │  - Order Processing        │    │
│  │  - Product Management      │    │
│  │  - User Management         │    │
│  └────────┬───────────────────┘    │
│           │                         │
│  ┌────────▼───────────────────┐    │
│  │    Prisma ORM              │    │
│  └────────┬───────────────────┘    │
└───────────┼─────────────────────────┘
            │
     ┌──────▼──────┐
     │   SQLite    │
     │  Database   │
     └─────────────┘
```

### Request Flow

1. **Client Request**: User interacts with UI
2. **Routing**: Next.js App Router handles routing
3. **Authentication**: JWT middleware validates user session
4. **Business Logic**: Request processed by appropriate handler
5. **Database**: Prisma ORM executes database operations
6. **Response**: Data sent back to client

---

## Technology Stack

### Core Technologies

| Technology | Version | Purpose |
|------------|---------|---------|
| Next.js | 16.1.1 | React framework with App Router |
| React | 19.x | UI library |
| TypeScript | 5.7.x | Type-safe development |
| Prisma | 6.1.x | Database ORM |
| SQLite | - | Local database |

### UI & Styling

| Technology | Version | Purpose |
|------------|---------|---------|
| Tailwind CSS | 3.4.x | Utility-first CSS framework |
| shadcn/ui | - | Pre-built React components |
| Lucide React | 0.460.x | Icon library |

### Authentication & Security

| Technology | Version | Purpose |
|------------|---------|---------|
| bcrypt | 5.1.x | Password hashing |
| jsonwebtoken | 9.x | JWT token generation/verification |
| uuid | 11.x | Unique ID generation |

---

## Database Schema

### Entity Relationship Diagram

```
┌─────────────┐
│    User     │
├─────────────┤
│ id (PK)     │
│ email       │──┐
│ password    │  │
│ name        │  │
│ createdAt   │  │
│ updatedAt   │  │
└─────────────┘  │
                 │
            ┌────▼────────┐
            │    Order    │
            ├─────────────┤
            │ id (PK)     │
            │ userId (FK) │
            │ orderNumber │
            │ checkoutToken│
            │ paymentMethod│
            │ paidAt      │
            │ shippingInfo│
            │ totalCents  │
            │ createdAt   │
            │ updatedAt   │
            └────┬────────┘
                 │
            ┌────▼──────────┐
            │   OrderItem   │
            ├───────────────┤
            │ id (PK)       │
            │ orderId (FK)  │
            │ productId (FK)│
            │ quantity      │
            │ priceCents    │
            └────┬──────────┘
                 │
       ┌─────────▼────────┐
       │     Product      │
       ├──────────────────┤
       │ id (PK)          │
       │ sku (UNIQUE)     │
       │ name             │
       │ description      │
       │ priceCents       │
       │ image            │
       │ createdAt        │
       │ updatedAt        │
       └──────────────────┘
```

### Schema Details

#### User Model
```prisma
model User {
  id        String   @id @default(uuid())
  email     String   @unique
  password  String
  name      String?
  createdAt DateTime @default(now())
  updatedAt DateTime @updatedAt
  orders    Order[]
}
```

#### Product Model
```prisma
model Product {
  id          String      @id @default(uuid())
  sku         String      @unique
  name        String
  description String
  priceCents  Int
  image       String
  createdAt   DateTime    @default(now())
  updatedAt   DateTime    @updatedAt
  orderItems  OrderItem[]
}
```

#### Order Model
```prisma
model Order {
  id             String      @id @default(uuid())
  orderNumber    String      @unique
  checkoutToken  String      @unique
  userId         String
  user           User        @relation(fields: [userId], references: [id])
  paymentMethod  String
  paidAt         DateTime?
  shippingName   String
  shippingStreet String
  shippingCity   String
  shippingZip    String
  totalCents     Int
  createdAt      DateTime    @default(now())
  updatedAt      DateTime    @updatedAt
  items          OrderItem[]
}
```

#### OrderItem Model
```prisma
model OrderItem {
  id         String  @id @default(uuid())
  orderId    String
  order      Order   @relation(fields: [orderId], references: [id])
  productId  String
  product    Product @relation(fields: [productId], references: [id])
  quantity   Int
  priceCents Int
}
```

---

## API Documentation

### Authentication Endpoints

#### POST /api/auth/register

Register a new user account.

**Request Body:**
```json
{
  "email": "user@example.com",
  "password": "securepassword",
  "name": "John Doe" // optional
}
```

**Response (201 Created):**
```json
{
  "user": {
    "id": "uuid",
    "email": "user@example.com",
    "name": "John Doe"
  }
}
```

**Error Responses:**
- `400`: Missing email or password
- `409`: User already exists
- `500`: Internal server error

---

#### POST /api/auth/login

Authenticate user and receive JWT cookie.

**Request Body:**
```json
{
  "email": "user@example.com",
  "password": "securepassword"
}
```

**Response (200 OK):**
```json
{
  "user": {
    "id": "uuid",
    "email": "user@example.com",
    "name": "John Doe"
  }
}
```

Sets HttpOnly cookie: `auth-token`

**Error Responses:**
- `400`: Missing credentials
- `401`: Invalid credentials
- `500`: Internal server error

---

### Product Endpoints

#### GET /api/products

Retrieve all products.

**Response (200 OK):**
```json
{
  "products": [
    {
      "id": "uuid",
      "sku": "CHOC-001",
      "name": "Dunkle Schokolade 70%",
      "description": "...",
      "priceCents": 450,
      "image": "https://...",
      "createdAt": "2026-01-13T...",
      "updatedAt": "2026-01-13T..."
    }
  ]
}
```

---

### Cart & Checkout Endpoints

#### POST /api/cart/checkout

Create a new order (idempotent).

**Authentication:** Required (JWT cookie)

**Request Body:**
```json
{
  "checkoutToken": "uuid-v4",
  "paymentMethod": "fake_card",
  "shipping": {
    "name": "John Doe",
    "street": "Main St 123",
    "city": "Berlin",
    "zip": "10115"
  },
  "items": [
    {
      "productId": "uuid",
      "quantity": 2
    }
  ]
}
```

**Response (201 Created or 409 Conflict):**
```json
{
  "message": "Order created successfully",
  "order": {
    "id": "uuid",
    "orderNumber": "ORD-ABCD1234",
    "totalCents": 900,
    "paidAt": "2026-01-13T...",
    "items": [...]
  }
}
```

**Idempotency:**
- If `checkoutToken` already exists, returns existing order with 409 status
- Prevents duplicate orders from double-clicks or network retries

**Error Responses:**
- `400`: Invalid payment method or missing fields
- `401`: Not authenticated
- `500`: Internal server error

---

#### GET /api/orders/[orderNumber]

Retrieve order details.

**Authentication:** Required (JWT cookie)

**Response (200 OK):**
```json
{
  "order": {
    "id": "uuid",
    "orderNumber": "ORD-ABCD1234",
    "totalCents": 900,
    "paidAt": "2026-01-13T...",
    "shippingName": "John Doe",
    "shippingStreet": "Main St 123",
    "shippingCity": "Berlin",
    "shippingZip": "10115",
    "items": [...],
    "user": {...}
  }
}
```

**Error Responses:**
- `401`: Not authenticated
- `403`: Not authorized (different user's order)
- `404`: Order not found
- `500`: Internal server error

---

## Authentication Flow

### Registration Flow

```
1. User submits registration form
2. Frontend sends POST to /api/auth/register
3. Server validates input
4. Password hashed with bcrypt (10 rounds)
5. User created in database
6. JWT token generated
7. Token set in HttpOnly cookie
8. User object returned
9. Frontend redirects to homepage
```

### Login Flow

```
1. User submits login form
2. Frontend sends POST to /api/auth/login
3. Server retrieves user by email
4. Password verified with bcrypt.compare()
5. JWT token generated on success
6. Token set in HttpOnly cookie
7. User object returned
8. Frontend redirects to homepage
```

### Protected Route Access

```
1. Request to protected route
2. Middleware extracts auth-token cookie
3. JWT verified and decoded
4. User ID extracted from token
5. Request proceeds with user context
6. If invalid: 401 Unauthorized
```

---

## Checkout Flow & Idempotency

### Standard Checkout Flow

```
1. User adds products to cart (localStorage)
2. User navigates to checkout page
3. Client generates UUID checkout token
4. User fills shipping information
5. Client sends checkout request with token
6. Server validates user authentication
7. Server validates products exist
8. Server calculates total price
9. Server wraps in Prisma transaction:
   - Creates Order with unique checkoutToken
   - Creates OrderItems
10. On success: Order returned
11. Cart cleared from localStorage
12. User redirected to order confirmation
```

### Idempotency Mechanism

**Problem:** Network issues or user double-clicks can cause duplicate orders.

**Solution:** Use `checkoutToken` (UUID) as unique constraint.

```typescript
// Client generates token once per checkout attempt
const checkoutToken = uuid();

// Server checks if token already exists
const existingOrder = await prisma.order.findUnique({
  where: { checkoutToken }
});

if (existingOrder) {
  // Return existing order with 409 status
  return res.status(409).json({ order: existingOrder });
}

// Otherwise create new order in transaction
try {
  const order = await prisma.$transaction(async (tx) => {
    return await tx.order.create({
      data: {
        orderNumber: generateOrderNumber(),
        checkoutToken, // Unique constraint
        // ... other fields
      }
    });
  });
} catch (error) {
  // Handle unique constraint violation
  if (error.code === 'P2002') {
    // Race condition: token was just used
    const order = await prisma.order.findUnique({
      where: { checkoutToken }
    });
    return res.status(409).json({ order });
  }
}
```

**Benefits:**
- ✅ Prevents duplicate orders
- ✅ Safe for retries
- ✅ Handles race conditions
- ✅ RESTful 409 Conflict response

---

## File Structure

```
charliesschockoladenfabrik/
├── app/
│   ├── api/
│   │   ├── auth/
│   │   │   ├── login/route.ts
│   │   │   └── register/route.ts
│   │   ├── cart/
│   │   │   └── checkout/route.ts
│   │   ├── orders/
│   │   │   └── [orderNumber]/route.ts
│   │   └── products/
│   │       └── route.ts
│   ├── cart/
│   │   └── page.tsx
│   ├── checkout/
│   │   └── page.tsx
│   ├── login/
│   │   └── page.tsx
│   ├── orders/
│   │   └── [orderNumber]/page.tsx
│   ├── products/
│   │   └── [id]/page.tsx
│   ├── register/
│   │   └── page.tsx
│   ├── layout.tsx
│   ├── page.tsx
│   └── globals.css
├── components/
│   └── ui/
│       ├── button.tsx
│       ├── card.tsx
│       ├── input.tsx
│       └── label.tsx
├── lib/
│   ├── auth.ts
│   ├── prisma.ts
│   └── utils.ts
├── prisma/
│   ├── schema.prisma
│   ├── seed.ts
│   └── dev.db (generated)
├── .env
├── .env.example
├── .gitignore
├── next.config.ts
├── package.json
├── postcss.config.mjs
├── tailwind.config.ts
├── tsconfig.json
├── DOCS.md
└── README.md
```

---

## Development Guide

### Setup Development Environment

1. **Prerequisites:**
   - Node.js 18-20 (v18.0.0 - v20.x)
   - npm or pnpm

2. **Clone and Install:**
   ```bash
   git clone <repository-url>
   cd charliesschockoladenfabrik
   npm install
   ```

3. **Environment Configuration:**
   ```bash
   cp .env.example .env
   # Edit .env with your JWT_SECRET
   ```

4. **Database Setup:**
   ```bash
   npx prisma db push
   npm run prisma:seed
   ```

5. **Start Development Server:**
   ```bash
   npm run dev
   ```

### Development Workflow

1. **Make Changes** in `app/`, `components/`, or `lib/`
2. **Test Locally** at `http://localhost:3000`
3. **Run Type Check:** `npx tsc --noEmit`
4. **Run Build:** `npm run build`
5. **Commit Changes**

### Adding New Features

#### Adding a New Page

```typescript
// app/my-page/page.tsx
export default function MyPage() {
  return <div>My Content</div>;
}
```

#### Adding a New API Route

```typescript
// app/api/my-endpoint/route.ts
import { NextRequest, NextResponse } from "next/server";

export async function GET(request: NextRequest) {
  return NextResponse.json({ data: "hello" });
}
```

#### Adding a New Component

```typescript
// components/ui/my-component.tsx
export function MyComponent({ children }: { children: React.ReactNode }) {
  return <div className="...">{children}</div>;
}
```

---

## Deployment

### Production Build

```bash
# Build for production
npm run build

# Start production server
npm run start
```

### Environment Variables

**Required:**
- `DATABASE_URL`: Path to SQLite database
- `JWT_SECRET`: Secret key for JWT signing

**Example Production .env:**
```
DATABASE_URL="file:./prisma/prod.db"
JWT_SECRET="your-super-secret-production-key-min-32-chars"
```

### Deployment Checklist

- [ ] Set strong `JWT_SECRET`
- [ ] Run `npm run build` successfully
- [ ] Database migrated/pushed
- [ ] Database seeded with initial data
- [ ] Test all critical flows:
  - [ ] Registration
  - [ ] Login
  - [ ] Product browsing
  - [ ] Add to cart
  - [ ] Checkout
  - [ ] Order confirmation

---

## Troubleshooting

### Build Errors

**Error:** `Module not found`
```bash
# Clean install
rm -rf node_modules package-lock.json
npm install
```

**Error:** `Prisma Client not generated`
```bash
npx prisma generate
```

### Runtime Errors

**Error:** `Database file not found`
```bash
npx prisma db push
npm run prisma:seed
```

**Error:** `JWT verification failed`
- Check `JWT_SECRET` in `.env`
- Clear browser cookies
- Try logging in again

---

## Support

For issues, questions, or contributions:
1. Check this documentation
2. Review the README.md
3. Check the codebase comments

---

**Last Updated:** January 13, 2026  
**Version:** 1.0.0  
**Next.js Version:** 16.1.1
