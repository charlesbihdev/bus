import { Link, usePage } from '@inertiajs/react';
import { Bus } from 'lucide-react';
import { dashboard, home, login } from '@/routes';
import type { Auth } from '@/types';

export default function PublicLayout({
    children,
}: {
    children: React.ReactNode;
}) {
    const user = usePage<{ auth: Auth }>().props.auth?.user;

    return (
        <div className="min-h-screen bg-gradient-to-b from-brand-yellow/10 via-background to-background text-foreground">
            <header className="sticky top-0 z-10 border-b border-black/5 bg-white/70 backdrop-blur-xl dark:border-white/10 dark:bg-neutral-950/60">
                <div className="mx-auto flex h-16 max-w-5xl items-center justify-between px-4">
                    <Link
                        href={home()}
                        className="flex items-center gap-2 font-bold tracking-tight"
                    >
                        <span className="flex size-8 items-center justify-center rounded-lg bg-brand-red text-white">
                            <Bus className="size-4" />
                        </span>
                        <span className="text-lg">BookBus</span>
                    </Link>

                    <Link
                        href={user ? dashboard() : login()}
                        className="rounded-full px-4 py-1.5 text-sm font-medium text-neutral-700 transition-colors hover:bg-black/5 dark:text-neutral-200 dark:hover:bg-white/10"
                    >
                        {user ? 'Dashboard' : 'Log in'}
                    </Link>
                </div>
            </header>

            <main>{children}</main>
        </div>
    );
}
