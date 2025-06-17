import React, { useState, useEffect, useRef } from 'react';
import { router } from '@inertiajs/react';
import AppLayout from '@/Layouts/AppLayout';
import axios from 'axios';

export default function Editor({ note }) {
  const [title, setTitle] = useState(note?.title || '');
  const [content, setContent] = useState(note?.content || '');
  const [aiLoading, setAiLoading] = useState(false);

  const lastSavedContent = useRef({ title, content });

  const saveNote = () => {
    if (note?.id) {
      router.put(`/notes/${note.id}`, { title, content });
    } else {
      router.post('/notes', { title, content });
    }
  };

  const enhanceNote = async () => {
    setAiLoading(true);
    try {
      const response = await axios.post('/api/notes/enhance', { content });
      setContent(response.data.result || '');
    } catch (error) {
      console.error('AI error:', error);
      alert('AI enhancement failed.');
    } finally {
      setAiLoading(false);
    }
  };

  // Auto-save after 2 seconds of inactivity
  useEffect(() => {
    const delayDebounce = setTimeout(() => {
      if (
        note?.id &&
        (lastSavedContent.current.title !== title || lastSavedContent.current.content !== content)
      ) {
        axios.put(`/notes/${note.id}`, { title, content }, {
		  headers: {
			Accept: 'application/json',
			'X-Requested-With': 'XMLHttpRequest',
		  }
		}).then(() => {
		  lastSavedContent.current = { title, content };
		}).catch(err => {
		  console.error('Auto-save error:', err);
		});


      }
    }, 2000); // wait 2 seconds

    return () => clearTimeout(delayDebounce);
  }, [title, content]);

  return (
    <AppLayout>
      <div className="p-6 space-y-4">
        <input
          type="text"
          value={title}
          onChange={(e) => setTitle(e.target.value)}
          className="w-full border px-4 py-2 text-xl font-bold"
          placeholder="Note Title"
        />

        <textarea
          value={content}
          onChange={(e) => setContent(e.target.value)}
          className="w-full h-64 border px-4 py-2"
          placeholder="Write your note here..."
        ></textarea>

        <div className="flex gap-4">
          <button
            onClick={saveNote}
            className="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"
          >
            Save
          </button>

          <button
            onClick={enhanceNote}
            disabled={aiLoading}
            className={`bg-blue-600 text-white px-4 py-2 rounded ${
              aiLoading ? 'opacity-50 cursor-wait' : 'hover:bg-blue-700'
            }`}
          >
            {aiLoading ? 'Enhancing...' : 'Enhance with AI'}
          </button>

          {note?.id && (
            <button
              onClick={() => {
                if (confirm('Are you sure you want to delete this note?')) {
                  router.delete(`/notes/${note.id}`);
                }
              }}
              className="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700"
            >
              Delete
            </button>
          )}
        </div>
      </div>
    </AppLayout>
  );
}
