import React from 'react';
import { Link, usePage } from '@inertiajs/react';

export default function AppLayout({ children }) {
  const { auth } = usePage().props;

  return (
    <div className="min-h-screen bg-gray-50 text-gray-800">
      <header className="bg-white shadow p-4 flex justify-between items-center">
        <h1 className="text-xl font-bold">
          <Link href="/dashboard">📝 AI Note Editor</Link>
        </h1>
        {auth?.user && (
          <div className="flex items-center gap-4">
            <span>{auth.user.name}</span>
            <img
              src={auth.user.avatar}
              alt="avatar"
              className="w-8 h-8 rounded-full"
            />
            <Link
              href="/logout"
              method="post"
              className="text-red-600 hover:underline"
            >
              Logout
            </Link>
          </div>
        )}
      </header>

      <main className="p-6">{children}</main>
    </div>
  );
}
