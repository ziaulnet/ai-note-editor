import React from 'react';
import { Link } from '@inertiajs/react';
import AppLayout from '@/Layouts/AppLayout';

export default function Dashboard({ auth, notes }) {
  return (
    <AppLayout>
      <div className="p-6">
        <h1 className="text-2xl font-bold mb-4">My Notes</h1>

        <Link
          href="/notes/create"
          className="inline-block mb-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
        >
          + New Note
        </Link>

        <ul className="space-y-2">
          {notes.map((note) => (
            <li key={note.id}>
              <Link
                href={`/notes/${note.id}/edit`}
                className="block p-4 bg-gray-100 rounded hover:bg-gray-200"
              >
                {note.title || 'Untitled Note'}
              </Link>
            </li>
          ))}
        </ul>
      </div>
    </AppLayout>
  );
}
