# Хром-KZ - Transportation Registry System

## Overview

This is a logistics management system designed for Kazakhstan, built as a full-stack web application. The system manages transportation requests with a role-based workflow that includes five different user roles: Прораб (Foreman), Логист (Logistician), Руководитель СМТ (SMT Manager), Финансовый директор (Financial Director), and Генеральный директор (General Director). The application facilitates the creation, approval, and tracking of transportation requests through a multi-stage approval process.

## System Architecture

### Frontend Architecture
- **Framework**: React 18 with TypeScript
- **Styling**: Tailwind CSS with shadcn/ui component library
- **Routing**: Wouter for client-side routing
- **State Management**: TanStack Query (React Query) for server state management
- **Forms**: React Hook Form with Zod validation
- **Charts**: Chart.js for data visualization
- **Build Tool**: Vite for development and production builds

### Backend Architecture
- **Runtime**: Node.js with Express.js framework
- **Language**: TypeScript with ES modules
- **Authentication**: Replit Auth with OpenID Connect integration
- **Session Management**: Express sessions with PostgreSQL storage
- **API Design**: RESTful API with role-based access control

### Database Architecture
- **Database**: PostgreSQL (configured for Neon Database)
- **ORM**: Drizzle ORM for type-safe database operations
- **Schema Management**: Drizzle Kit for migrations and schema management
- **Connection**: Neon serverless PostgreSQL with WebSocket support

## Key Components

### Authentication System
- Replit Auth integration with OpenID Connect
- Session-based authentication using PostgreSQL session store
- Role-based access control with five distinct user roles
- Automatic user creation and profile management

### Transportation Request Management
- Multi-stage approval workflow based on user roles
- Request creation, editing, and status tracking
- Comments system for request collaboration
- Automatic request numbering and categorization

### Dashboard and Analytics
- Real-time statistics and metrics
- Monthly transportation trends
- Status distribution charts
- Recent requests overview

### Internationalization
- Multi-language support (Russian, Kazakh, English)
- Localized city names, cargo types, and transport options
- Role-based UI text translation

## Data Flow

1. **Request Creation**: Прораб creates transportation requests with basic cargo information
2. **Logistics Review**: Логист reviews and adds logistics details (transport type, carrier, scheduling)
3. **Management Approval**: Руководитель СМТ reviews and approves/rejects requests
4. **Financial Review**: Финансовый директор adds cost information and final approval
5. **Executive Oversight**: Генеральный директор has full system access and override capabilities

### Role-Based Permissions
- **Прораб**: Create and edit own requests in "created" status
- **Логист**: Process requests in "created" status, move to "logistics"
- **Руководитель СМТ**: Review "logistics" status requests, approve/reject
- **Финансовый директор**: Handle "manager" approved requests, add financial data
- **Генеральный директор**: Full access to all requests and system functions

## External Dependencies

### Core Dependencies
- **@neondatabase/serverless**: Serverless PostgreSQL connection
- **drizzle-orm**: Type-safe ORM for database operations
- **@radix-ui/**: Complete UI component primitives
- **@tanstack/react-query**: Server state management
- **express-session**: Session management middleware
- **connect-pg-simple**: PostgreSQL session store

### Development Tools
- **Vite**: Frontend build tool and development server
- **TypeScript**: Type safety across the entire stack
- **Tailwind CSS**: Utility-first CSS framework
- **ESBuild**: Fast JavaScript bundler for production

### Authentication
- **openid-client**: OpenID Connect client implementation
- **passport**: Authentication middleware framework
- **memoizee**: Function memoization for performance

## Deployment Strategy

### Environment Configuration
- **Development**: Local development with Vite dev server and TypeScript compilation
- **Production**: Optimized builds with Vite for frontend and ESBuild for backend
- **Database**: Neon PostgreSQL with automatic scaling
- **Session Storage**: PostgreSQL-backed session management

### Build Process
1. Frontend assets compiled with Vite to `dist/public`
2. Backend TypeScript compiled with ESBuild to `dist/index.js`
3. Static assets served from Express in production
4. Database migrations handled via Drizzle Kit

### Replit Integration
- Configured for Replit deployment with autoscale
- Environment variables for database and session configuration
- Integrated development workflow with live reloading
- Port configuration for external access

## Changelog

Changelog:
- June 27, 2025. Initial setup - Created multi-role transportation registry system
- June 27, 2025. User role updated to генеральный директор for full system access
- January 3, 2025. Added logistics management tools:
  - Carrier Management: Database of transport companies with ratings and contact info
  - Route Optimization: Route planning and optimization tools
  - Cost Calculator: Transportation cost calculation with detailed breakdown
  - Tracking System: Real-time shipment tracking and monitoring
- January 3, 2025. Migration from Replit Agent to Replit environment completed
- January 3, 2025. Added Super Admin functionality:
  - Super Admin role (супер_админ) with complete system access
  - Comprehensive admin panel for managing users, requests, carriers, routes, and system data
  - Role-based navigation hiding super admin features from regular users
  - Full CRUD operations for all system entities
- January 3, 2025. Replaced Replit Auth with custom username/password authentication:
  - Custom login and registration forms with bcrypt password hashing
  - Session-based authentication using PostgreSQL storage
  - Role assignment restricted to General Director only
  - New users default to "прораб" role during registration
- July 4, 2025. Migration to Replit environment completed:
  - Successfully migrated from Replit Agent to standard Replit environment
  - Database setup and schema migrations completed
  - Session management fixed and stabilized
  - Fixed PostgreSQL session index conflict issue
  - All core functionality tested and verified working:
    * User registration and authentication
    * Transportation request creation and retrieval
    * Role-based access control
    * Database operations functioning correctly
- July 4, 2025. Enhanced transportation request form:
  - Added destination address field alongside destination city selection
  - Expanded city list to include 20 major Kazakhstan cities with multilingual support
  - Updated database schema to store delivery addresses
  - Customized cargo types based on Хром KZ and Glass House companies in Astana
  - Added specialized categories for stainless steel products, glass items, railings, elevators, and architectural elements
  - Added cargo dimensions fields (width, length, height) with metric units
  - Redesigned requests list page with modern card-based layout and functional edit capabilities
  - Implemented comprehensive edit dialog with all form fields and proper validation
  - Added status filtering, approval/rejection workflows, and role-based permissions
  - Applied modern UI design with gradients, backdrop blur effects, animations, and visual status indicators
  - Enhanced user experience with interactive elements, hover effects, and responsive layout
- July 4, 2025. Created БОГ АДМИНКА (God Admin Panel):
  - Added new "супер_админ" role with ultimate system access
  - Created comprehensive GodAdminDashboard component with full CRUD operations
  - Implemented advanced admin interface with tabbed navigation for all system entities
  - Added complete user management with role editing capabilities
  - Included full transportation request management with status controls and deletion
  - Built carrier management system with creation, editing, and removal functions  
  - Integrated route management with cost tracking and optimization features
  - Added shipment tracking overview with progress monitoring
  - Designed dramatic black/red themed UI with crown icons and gradient effects
  - All operations include real-time updates and user feedback notifications
- July 4, 2025. Migration to Replit environment completed successfully:
  - Successfully migrated from Replit Agent to standard Replit environment
  - Fixed all Git merge conflicts in codebase files (Layout.tsx, Home.tsx, routes.ts)
  - Set up PostgreSQL database with proper schema using Drizzle migrations
  - Resolved session management issues and authentication system
  - Fixed transportation request creation API with proper field mapping
  - Verified full system functionality including user authentication, request CRUD operations, and dashboard analytics
  - All core features tested and working correctly including registration, login, request creation, and data retrieval
  - System is now ready for production use in standard Replit environment
- July 7, 2025. Final migration to Replit environment completed:
  - Successfully completed migration from Replit Agent to standard Replit environment
  - PostgreSQL database provisioned and configured with all necessary tables
  - Database schema pushed using Drizzle migrations
  - Created test super admin account (admin@test.com/admin123) with full system access
  - All workflows running successfully with server on port 5000
  - System fully operational and ready for production use
- July 7, 2025. Google Cloud deployment files prepared:
  - Created comprehensive deployment configuration for Google Cloud Platform
  - App Engine configuration with auto-scaling and security
  - Docker containerization support
  - Cloud Build CI/CD pipeline setup
  - Health monitoring and backup strategies
  - Complete deployment documentation and guides
- July 7, 2025. Bitrix24 integration module developed:
  - Created full-featured Bitrix24 integration module for transport registry
  - Automatic task creation for transport requests with role-based assignment
  - CRM synchronization for carriers with custom fields and ratings
  - Webhook handlers for real-time data synchronization
  - REST API client for external application integration
  - Complete installation and configuration system
  - User role mapping and notification system
  - Support for both local module and external app deployment methods
- July 10, 2025. Yandex Cloud deployment package prepared:
  - Complete Yandex Cloud deployment configuration with Serverless Containers
  - Dockerfile optimized for Yandex Cloud environment
  - Terraform infrastructure as code for automated provisioning
  - Automated deployment script with Container Registry integration
  - Health monitoring and logging configuration
  - Nginx reverse proxy with SSL termination
  - Database migration and initialization scripts
  - Support for both managed and external PostgreSQL databases
  - Cost optimization and scaling configuration
  - Security hardening with proper access controls
- July 7, 2025. Final migration to Replit environment completed:
  - Successfully migrated from Replit Agent to standard Replit environment
  - Created PostgreSQL database with all required tables and schema
  - Server running successfully on port 5000
  - Created test super admin account: admin@test.com / admin123
  - All database tables created: users, transportation_requests, carriers, routes, shipments, tracking_points, etc.
  - System fully functional and ready for use
- July 16, 2025. Complete PHP Backend Implementation:
  - Created full PHP rewrite of the Node.js/TypeScript backend
  - Complete API compatibility with existing React frontend
  - All models implemented: User, TransportationRequest, Carrier, Route, Shipment
  - Session-based authentication with bcrypt password hashing
  - PostgreSQL integration using PDO with prepared statements
  - RESTful API endpoints for all system functions
  - CORS support for frontend integration
  - Role-based access control matching original system
  - PHP 8.2+ with all required extensions installed
  - Drop-in replacement for Node.js backend requiring no frontend changes

## User Preferences

Preferred communication style: Simple, everyday language.

## Recent Changes

- July 17, 2025. Migration from Replit Agent to Replit Environment Completed Successfully:
  - Successfully migrated the entire application from Replit Agent to standard Replit environment
  - PostgreSQL database created and configured with all required tables and schema
  - Fixed all authentication and session management issues
  - Modern bright design implemented with purple/pink gradients and glass morphism effects
  - All core functionality tested and verified working:
    * User registration and login system
    * Transportation request creation and management
    * Dashboard statistics and analytics
    * Role-based access control
    * All API endpoints functioning correctly
  - Beautiful modern UI with floating animations, gradient backgrounds, and smooth transitions
  - Server running stable on port 5000 with no errors
  - Test accounts created for development and testing
  - System fully operational and ready for production use
- July 17, 2025. NEW: Complete Modern Dark UI Implementation:
  - Completely redesigned entire application with modern dark theme
  - Replaced all UI elements with contemporary dark glass morphism design
  - New login page with animated background elements and modern form styling
  - Dark header with gradient logos and improved user profile display
  - Modern sidebar navigation with gradient hover effects and section indicators
  - Dashboard redesigned with dark cards, neon effects, and animated backgrounds
  - Custom CSS with advanced animations, glow effects, and smooth transitions
  - Grid pattern backgrounds, backdrop blur effects, and modern color schemes
  - Enhanced typography with gradient text effects and improved spacing
  - Full dark theme implementation across entire PHP application
- July 17, 2025. Ultra-Modern UI Enhancement:
  - Implemented complete ultra-modern design with dark theme and glass morphism
  - Added neon gradients, floating animations, and interactive micro-animations
  - Created responsive animated backgrounds with radial gradients
  - Enhanced all buttons with hover effects, scale animations, and proper click handlers
  - Added functional search, export, filter, and refresh capabilities
  - Implemented proper navigation with Link components and active states
  - Added confirmation dialogs and user feedback for all interactive elements
  - Created modern loading states and error handling
  - All UI components now fully functional with proper event handlers
- July 17, 2025. Minimalist Design Implementation:
  - Redesigned entire application with clean minimalist white theme
  - Replaced ultra-modern dark theme with light, clean aesthetic
  - Created MinimalLayout, MinimalAuthForm, and MinimalDashboard components
  - Simplified color scheme with white backgrounds and subtle borders
  - Implemented clean CSS classes for buttons, cards, and inputs
  - Removed glass morphism effects in favor of clean shadows and borders
  - Added subtle hover effects and minimal animations
  - Updated all components to use new minimalist design system
  - Maintained all functionality while simplifying visual design