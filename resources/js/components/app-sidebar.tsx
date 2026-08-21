import { Link } from '@inertiajs/react';
import { BusFront, ExternalLink, LayoutGrid, MapPin, Route as RouteIcon, TicketCheck } from 'lucide-react';
import { index as adminBookings } from '@/actions/App/Http/Controllers/Admin/BookingController';
import { index as adminBuses } from '@/actions/App/Http/Controllers/Admin/BusController';
import { index as adminRoutes } from '@/actions/App/Http/Controllers/Admin/RouteController';
import { index as adminTowns } from '@/actions/App/Http/Controllers/Admin/TownController';
import AppLogo from '@/components/app-logo';
import { NavFooter } from '@/components/nav-footer';
import { NavMain } from '@/components/nav-main';
import { NavUser } from '@/components/nav-user';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard, home } from '@/routes';
import type { NavItem } from '@/types';

const mainNavItems: NavItem[] = [
    {
        title: 'Overview',
        href: dashboard(),
        icon: LayoutGrid,
    },
    {
        title: 'Bookings',
        href: adminBookings(),
        icon: TicketCheck,
    },
    {
        title: 'Routes',
        href: adminRoutes(),
        icon: RouteIcon,
    },
    {
        title: 'Towns',
        href: adminTowns(),
        icon: MapPin,
    },
    {
        title: 'Buses',
        href: adminBuses(),
        icon: BusFront,
    },
];

const footerNavItems: NavItem[] = [
    {
        title: 'Booking site',
        href: home(),
        icon: ExternalLink,
    },
];

export function AppSidebar() {
    return (
        <Sidebar collapsible="icon" variant="inset">
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton size="lg" asChild>
                            <Link href={dashboard()} prefetch>
                                <AppLogo />
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>

            <SidebarContent>
                <NavMain items={mainNavItems} />
            </SidebarContent>

            <SidebarFooter>
                <NavFooter items={footerNavItems} className="mt-auto" />
                <NavUser />
            </SidebarFooter>
        </Sidebar>
    );
}
